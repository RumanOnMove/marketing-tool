<?php

namespace Moveon\EmailTemplate\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use Moveon\EmailTemplate\Http\Resources\EmailTemplateResource;
use Moveon\EmailTemplate\Mail\CampaignMail;
use Moveon\EmailTemplate\Models\EmailTemplate;
use Moveon\EmailTemplate\Models\EmailTemplateTag;
use Moveon\EmailTemplate\Models\FeatureEmailTemplate;
use Moveon\EmailTemplate\Services\EmailTemplateService;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class EmailTemplateController extends Controller
{
    private EmailTemplateService $emailTemplateService;

    public function __construct(EmailTemplateService $emailTemplateService) {
        $this->emailTemplateService = $emailTemplateService;
    }

    /**
     * List of email templates
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        # Validate Request
        $request->validate([
            'name'     => 'nullable|string|max:255',
            'per_page' => 'nullable|integer',
            'sort_by'  => 'nullable|string|in:' . implode(',', EmailTemplate::SORT_BY),
        ]);

        # Get data
        $templates = $this->emailTemplateService->getTemplates($request);
        $templates = EmailTemplateResource::collection($templates);

        # Build collection response for pagination
        $response = $this->collectionResponse($templates);

        # Return response
        return Response::json($response, ResponseAlias::HTTP_OK);
    }

    /**
     * Single email template
     * @param $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        # Get data
        $template = $this->emailTemplateService->getTemplate($id);

        # Check data
        if (!$template) {
            # Return response
            return Response::json([
                'error' => 'Not found!'
            ], ResponseAlias::HTTP_NOT_FOUND);
        }

        $template = new EmailTemplateResource($template);

        # Return response
        return Response::json([
            'data' => $template
        ], ResponseAlias::HTTP_OK);
    }

    /** Storing email template
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        # Validate Request
        $request->validate([
            'name'   => 'required|string|max:255|unique:email_templates,name',
            'design' => 'required|array',
            'html'   => 'required|string',
            'status' => 'required|string|in:'.implode(',', EmailTemplate::STATUS),
        ]);

        # Sanitize data
        $data = $request->only('name', 'design', 'html', 'status');

        # Create data
        $templates = $this->emailTemplateService->createTemplate($data);
        $templates = new EmailTemplateResource($templates);

        # Return response
        return Response::json([
            'data' => $templates
        ], ResponseAlias::HTTP_CREATED);
    }

    /**
     * Updating email template
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        # Validate Request
        $request->validate([
            'name'   => 'required|string|max:255|unique:email_templates,name,' . $id,
            'design' => 'nullable|array',
            'html'   => 'nullable|string',
            'status' => 'nullable|string|in:' . implode(',', EmailTemplate::STATUS),
        ]);

        # Sanitize data
        $data = $request->only('name', 'design', 'html', 'status');

        # Update data
        $templateU = $this->emailTemplateService->updateTemplate($id, $data);

        # Check data
        if (!$templateU) {
            # Return response
            return Response::json([
                'error' => 'Not found'
            ], ResponseAlias::HTTP_NOT_FOUND);
        }

        $templateU = new EmailTemplateResource($templateU);

        # Return response
        return Response::json([
            'data' => $templateU
        ], ResponseAlias::HTTP_OK);
    }

    /**
     * Deleting email template
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        # Delete data
        $template = $this->emailTemplateService->deleteTemplate($id);

        # Check data
        if (!$template) {
            # Return response
            return Response::json([
                'error' => 'Not found'
            ], ResponseAlias::HTTP_NOT_FOUND);
        }

        # Return response
        return Response::json([
            'message' => 'Template deleted successfully!'
        ], ResponseAlias::HTTP_OK);
    }

    public function sendMail() {
        # Get data
        $template = $this->emailTemplateService->getTemplate(7);
        $tags = EmailTemplateTag::select('value')->get();
        $placeholders = [];
        foreach ($tags as $tag) {
            switch ($tag) {
                case $tag->value['value'] === '{{first_name}}':
                    $placeholders[] = [$tag->value['value'] => 'John'];
                    break;
                case  $tag->value['value'] === '{{last_name}}':
                    $placeholders[] = [$tag->value['value'] => 'Doe'];
                    break;
                case $tag->value['value'] === '{{company_name}}':
                    $placeholders[] = [$tag->value['value'] => 'SpaceX'];
                    break;
            }
        }
        $emailTemplate = Str::replacePlaceholder($template->html, $placeholders);
        Mail::to('user@example.com')->send(new CampaignMail($emailTemplate));
    }
}

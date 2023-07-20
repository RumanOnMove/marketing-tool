<?php

namespace Moveon\EmailTemplate\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Moveon\EmailTemplate\Http\Resources\EmailTemplateResource;
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
            'name' => 'nullable|string|max:255',
            'per_page' => 'nullable|integer',
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
            'name'         => 'required|string|max:255|unique:email_templates,name',
            'subject'      => 'nullable|string|max:255',
            'type'         => 'nullable|string|max:55',
            'placeholders' => 'nullable|array',
            'content'      => 'nullable|string',
        ]);

        # Sanitize data
        $data = $request->only('name', 'subject', 'type', 'placeholders', 'content');

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
            'name'         => 'required|string|max:255|unique:email_templates,name,'.$id,
            'subject'      => 'nullable|string|max:255',
            'type'         => 'nullable|string|max:55',
            'placeholders' => 'nullable|array',
            'content'      => 'nullable|string',
        ]);

        # Sanitize data
        $data = $request->only('name', 'subject', 'type', 'placeholders', 'content');

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
}

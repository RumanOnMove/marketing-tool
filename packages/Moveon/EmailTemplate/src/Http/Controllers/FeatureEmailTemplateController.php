<?php

namespace Moveon\EmailTemplate\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Moveon\EmailTemplate\Http\Resources\EmailTemplateResource;
use Moveon\EmailTemplate\Services\FeatureEmailTemplateService;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class FeatureEmailTemplateController extends Controller
{
    private FeatureEmailTemplateService $featureEmailTemplateService;

    public function __construct(FeatureEmailTemplateService $featureEmailTemplateService) {
        $this->featureEmailTemplateService = $featureEmailTemplateService;
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
        $templates = $this->featureEmailTemplateService->getTemplates($request);
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
        $template = $this->featureEmailTemplateService->getTemplate($id);

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

    /**
     * Attach feature template to my template
     * @param $id
     * @return JsonResponse
     */
    public function addToMyTemplates($id): JsonResponse
    {
        # Get data
        $template = $this->featureEmailTemplateService->getTemplate($id);

        try {
            # Check data
            if (!$template) {
                # Return response
                return Response::json([
                    'error' => 'Not found!'
                ], ResponseAlias::HTTP_NOT_FOUND);
            }

            $data = $template->only('name', 'design', 'html');

            $attach = $this->featureEmailTemplateService->attachFeatureWithMyTemplate($data);
            if ($attach) {
                return Response::json([
                    'message' => 'Template attach to my template successfully'
                ], ResponseAlias::HTTP_OK);
            }
        } catch (\Exception $ex) {
            if ($ex->getCode() === '23000') {
                return Response::json([
                    'error' => 'You already have same name in my template'
                ], ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
            }
        }

        return Response::json([
            'error' => 'Could not attach. Please try later'
        ], ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
    }
}

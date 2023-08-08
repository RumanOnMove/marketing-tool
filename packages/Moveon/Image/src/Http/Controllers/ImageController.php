<?php

namespace Moveon\Image\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Moveon\Image\Http\Resources\ImageResource;
use Moveon\Image\Services\ImageService;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ImageController extends Controller
{
    private ImageService $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    /**
     * List of image
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        # Validate request
        $request->validate([
            'name'     => 'nullable|string',
            'per_page' => 'nullable|integer',
        ]);

        # Get data
        $images = $this->imageService->getImages($request);
        $images = ImageResource::collection($images);

        # Build collection response for pagination
        $response = $this->collectionResponse($images);

        # Return response
        return Response::json($response, ResponseAlias::HTTP_OK);

    }
}

<?php

namespace Moveon\Image\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Moveon\Image\Http\Resources\ImageResource;
use Moveon\Image\Models\Category;
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
        # Transform image
        $images = ImageResource::collection($images);

        # Build collection response for pagination
        $response = $this->collectionResponse($images);

        # Return response
        return Response::json($response, ResponseAlias::HTTP_OK);
    }

    /**
     * Store image
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        # Validate data
        $request->validate([
            'image'        => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'categories'   => 'nullable|array',
            'categories.*' => 'nullable|integer|in:' . implode(',', Category::all()->pluck('id')->toArray())
        ]);

        # Create image
        $image = $this->imageService->createImage($request);

        if (!$image) {
            # Return response
            return Response::json([
                'error' => 'Could not create image. Please try later.'
            ], ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
        }

        # Load categories
        $image = $image->load('categories');
        # Transform image
        $image = new ImageResource($image);

        # Return response
        return Response::json([
            'data' => $image
        ], ResponseAlias::HTTP_CREATED);
    }

    /**
     * Show image
     * @param $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        # Get data
        $image = $this->imageService->getImage($id);

        # Check data
        if (!$image) {
            return Response::json([
                'error' => 'Not found!'
            ], ResponseAlias::HTTP_NOT_FOUND);
        }

        # Load categories
        $image = $image->load('categories');
        # Transform image
        $image = new ImageResource($image);

        # Return response
        return Response::json([
            'data' => $image
        ], ResponseAlias::HTTP_OK);
    }

    /**
     * Update image
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        # Validate data
        $request->validate([
            'categories'   => 'required|array',
            'categories.*' => 'required|in:' . implode(',', Category::all()->pluck('id')->toArray())
        ]);

        # Sanitize data
        $data = $request->input('categories');

        $imageU = $this->imageService->updateImage($id, $data);

        # If not update
        if (!$imageU) {
            # Return response
            return Response::json([
                'error' => 'Could not update image. Please try later.'
            ], ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
        }

        $imageU = $imageU->fresh();
        # Transform image
        $imageU = new ImageResource($imageU->load('categories'));

        # Return response
        return Response::json([
            'data' => $imageU
        ], ResponseAlias::HTTP_OK);
    }
}

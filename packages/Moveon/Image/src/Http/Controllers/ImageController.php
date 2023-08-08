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
        $images = ImageResource::collection($images);

        # Build collection response for pagination
        $response = $this->collectionResponse($images);

        # Return response
        return Response::json($response, ResponseAlias::HTTP_OK);
    }

    public function store(Request $request) {
        # Validate data
        $request->validate([
            'image'    => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category' => 'nullable|integer|in:' . implode(',', Category::all()->pluck('id')->toArray())
        ]);

        # Create image
        $image = $this->imageService->createImage($request);
        dd($image);
    }
}

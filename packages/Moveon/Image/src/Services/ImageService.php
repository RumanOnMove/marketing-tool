<?php

namespace Moveon\Image\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Moveon\Image\Repositories\ImageRepository;

class ImageService
{
    private ImageRepository $imageRepository;

    public function __construct(ImageRepository $imageRepository)
    {
        $this->imageRepository = $imageRepository;
    }

    /**
     * Get all image
     * @param $request
     * @return LengthAwarePaginator
     */
    public function getImages($request): LengthAwarePaginator
    {
       return $this->imageRepository->all($request);
    }
}

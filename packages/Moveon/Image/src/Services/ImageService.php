<?php

namespace Moveon\Image\Services;

use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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

    /**
     * @param $request
     */
    public function createImage($request)
    {
        # Get info from image
        $image             = $request->file('image');
        $imageUniqueName   = time() . '.' . $image->getClientOriginalExtension();
        $imageOriginalName = $image->getClientOriginalName();
        $imageType         = $image->getClientMimeType();

        # Store image in storage
        $path = $image->storeAs('images', $imageUniqueName, 'public');

        # Sanitize data
        $data = [
            'name' => $imageOriginalName,
            'type' => $imageType,
            'link' => $path
        ];

        try {
            DB::beginTransaction();
            $image = $this->imageRepository->create($data);
            if ($image) {
                # Attach image with category
                $image->categories()->attach($request->input('category'));

                DB::commit();

                # Return fresh image
                return $image;
            }
            throw new Exception('Could not create image. Please try later');
        } catch (Exception $ex) {
            DB::rollBack();
            Log::critical($ex->getMessage());
            return [
                'error' => $ex->getMessage()
            ];
        }
    }

    /**
     * Get Image
     * @param $id
     * @return Model|Collection|Builder|array|null
     */
    public function getImage($id): Model|Collection|Builder|array|null
    {
        $image = $this->imageRepository->find($id);

        if (!$image) {
            return null;
        }

        return $image;
    }
}

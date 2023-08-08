<?php

namespace Moveon\Image\Repositories;

use Moveon\Image\Models\Image;

class ImageRepository
{
    public function all($request) {
        $perPage = $request->input('per_page', 10);

        return Image::query()
            ->when($request->input('name'), function ($query) use($request) {
                $query->where('name', 'like', '%'. $request->input('name'. '%'));
            })
            ->paginate($perPage);
    }
}

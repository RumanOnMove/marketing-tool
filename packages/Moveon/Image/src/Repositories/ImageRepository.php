<?php

namespace Moveon\Image\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Moveon\Image\Models\Image;

class ImageRepository
{
    public function all($request): LengthAwarePaginator
    {
        $perPage = $request->input('per_page', 10);

        return Image::query()
            ->when($request->input('name'), function ($query) use($request) {
                $query->where('name', 'like', '%'. $request->input('name'. '%'));
            })
            ->with('categories')
            ->paginate($perPage);
    }

    public function create($data) {
        return Image::create($data);
    }

    public function find($id): Model|Collection|Builder|array|null
    {
        return Image::query()
            ->find($id);
    }
}

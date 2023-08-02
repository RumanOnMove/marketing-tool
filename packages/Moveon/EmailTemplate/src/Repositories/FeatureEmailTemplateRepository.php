<?php

namespace Moveon\EmailTemplate\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Moveon\EmailTemplate\Models\FeatureEmailTemplate;

class FeatureEmailTemplateRepository
{
    public function all($request): LengthAwarePaginator
    {
        $perPage = $request->input('per_page', 10);
        return FeatureEmailTemplate::query()
            ->when($request->input('name'), function ($query) use($request) {
                $query->where('name', 'like', '%'. $request->input('name') .'%');
            })
            ->paginate($perPage);
    }

    public function find($id): object|null
    {
        return FeatureEmailTemplate::query()
            ->where('id', $id)
            ->first();
    }
}

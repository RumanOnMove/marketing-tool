<?php

namespace Moveon\EmailTemplate\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Moveon\EmailTemplate\Models\EmailTemplate;

class EmailTemplateRepository
{
    public function all($request): LengthAwarePaginator
    {
        $perPage = $request->input('per_page', 10);
        return EmailTemplate::query()
            ->when($request->input('name'), function ($query) use($request) {
                $query->where('name', 'like', '%'. $request->input('name') .'%');
            })
            ->paginate($perPage);
    }

    public function create($data): mixed
    {
        return EmailTemplate::create($data);
    }
}

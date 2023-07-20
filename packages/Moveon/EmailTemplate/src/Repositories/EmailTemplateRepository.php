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

    public function find($id): object|null
    {
        return EmailTemplate::query()
            ->where('id', $id)
            ->first();
    }

    public function create($data): mixed
    {
        return EmailTemplate::create($data);
    }

    public function update($id, $data): void
    {
        $this->find($id)->update($data);
    }

    public function delete($id): bool|null
    {
        return $this->find($id)->delete();
    }
}

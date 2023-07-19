<?php

namespace Moveon\EmailTemplate\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Moveon\EmailTemplate\Models\EmailTemplate;

class EmailTemplateRepository
{
    public function all($perPage): LengthAwarePaginator
    {
        return EmailTemplate::query()
            ->paginate($perPage);
    }

    public function create($data): mixed
    {
        return EmailTemplate::create($data);
    }
}

<?php

namespace Moveon\EmailTemplate\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Moveon\EmailTemplate\Repositories\EmailTemplateRepository;

class EmailTemplateService
{
    private EmailTemplateRepository $emailTemplateRepository;

    public function __construct(EmailTemplateRepository $emailTemplateRepository) {
        $this->emailTemplateRepository = $emailTemplateRepository;
    }

    /**
     * Get all email template
     * @param $perPage
     * @return LengthAwarePaginator
     */
    public function getTemplates($request): LengthAwarePaginator
    {
        return $this->emailTemplateRepository->all($request);
    }

    /**
     * Create email template
     * @param $data
     * @return mixed
     */
    public function createTemplate($data): mixed
    {
        return $this->emailTemplateRepository->create($data);
    }
}

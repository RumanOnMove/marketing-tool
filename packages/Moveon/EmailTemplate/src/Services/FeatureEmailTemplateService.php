<?php

namespace Moveon\EmailTemplate\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Moveon\EmailTemplate\Repositories\EmailTemplateRepository;
use Moveon\EmailTemplate\Repositories\FeatureEmailTemplateRepository;

class FeatureEmailTemplateService
{
    private FeatureEmailTemplateRepository $featureEmailTemplateRepository;

    public function __construct(FeatureEmailTemplateRepository $featureEmailTemplateRepository) {
        $this->featureEmailTemplateRepository = $featureEmailTemplateRepository;
    }

    /**
     * Get all email template
     * @param $request
     * @return LengthAwarePaginator
     */
    public function getTemplates($request): LengthAwarePaginator
    {
        return $this->featureEmailTemplateRepository->all($request);
    }

    /**
     * Get a email template
     * @param $id
     * @return object|null
     */
    public function getTemplate($id): object|null
    {
        $template =  $this->featureEmailTemplateRepository->find($id);

        if (!$template) {
            return null;
        }

        return $template;
    }

    public function attachFeatureWithMyTemplate($data) {
        $emailTemplateRepository = new EmailTemplateRepository();

        return $emailTemplateRepository->create($data);
    }
}

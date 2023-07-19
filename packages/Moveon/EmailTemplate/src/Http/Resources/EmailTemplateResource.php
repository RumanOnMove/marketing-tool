<?php

namespace Moveon\EmailTemplate\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmailTemplateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     *
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
                'id' => $this->id,
                'name' => $this->name,
                'subject' => $this->subject,
                'type' => $this->type,
                'placeholders' => $this->placeholders,
                'content' => $this->content,
                'status' => $this->status,
            ];
    }
}

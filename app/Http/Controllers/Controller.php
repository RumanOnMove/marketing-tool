<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function collectionResponse($collections): array
    {
        return [
            'data' => $collections,
            'links' => [
                'first' => $collections->url(1),
                'last' => $collections->url($collections->lastPage()),
                'prev' => $collections->previousPageUrl(),
                'next' => $collections->nextPageUrl(),
            ],
            'meta' => [
                'current_page' => $collections->currentPage(),
                'from' => $collections->firstItem(),
                'last_page' => $collections->lastPage(),
                'path' => $collections->path(),
                'per_page' => $collections->perPage(),
                'to' => $collections->lastItem(),
                'total' => $collections->total(),
            ]
        ];
    }
}

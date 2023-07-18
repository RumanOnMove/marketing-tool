<?php

namespace Moveon\EmailTemplate\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Moveon\EmailTemplate\Models\EmailTemplate;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class EmailTemplateController extends Controller
{
    public function index() {
        $emailTemplates = EmailTemplate::all();
        return Response::json([
            'data' => $emailTemplates
        ], ResponseAlias::HTTP_OK);
    }
}

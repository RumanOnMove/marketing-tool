<?php

use Illuminate\Support\Facades\Route;
use Moveon\EmailTemplate\Http\Controllers\EmailTemplateController;

Route::group(['prefix' => 'api/v1/email-templates'], function () {
    Route::get('/', [EmailTemplateController::class, 'index']);
    Route::get('/{id}', [EmailTemplateController::class, 'show']);
    Route::post('/', [EmailTemplateController::class, 'store']);
    Route::put('/{id}', [EmailTemplateController::class, 'update']);
});

<?php

use Illuminate\Support\Facades\Route;
use Moveon\EmailTemplate\Http\Controllers\EmailTemplateController;
use Moveon\EmailTemplate\Http\Controllers\FeatureEmailTemplateController;

Route::group(['prefix' => 'v1/email-templates'], function () {
    Route::get('/', [EmailTemplateController::class, 'index']);
    Route::get('/{id}', [EmailTemplateController::class, 'show']);
    Route::post('/', [EmailTemplateController::class, 'store']);
    Route::put('/{id}', [EmailTemplateController::class, 'update']);
    Route::delete('/{id}', [EmailTemplateController::class, 'destroy']);
    Route::post('/send', [EmailTemplateController::class, 'sendMail']);
});

Route::group(['prefix' => 'v1/features-email-templates'], function () {
    Route::get('/', [FeatureEmailTemplateController::class, 'index']);
    Route::get('/{id}', [FeatureEmailTemplateController::class, 'show']);
    Route::post('/{id}/attach-to-my-template', [FeatureEmailTemplateController::class, 'addToMyTemplates']);
});

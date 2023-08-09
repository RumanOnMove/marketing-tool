<?php

use Illuminate\Support\Facades\Route;
use Moveon\Image\Http\Controllers\ImageController;


Route::group(['prefix' => 'v1/images'], function () {
    Route::get('/', [ImageController::class, 'index']);
    Route::post('/', [ImageController::class, 'store']);
    Route::get('/{id}', [ImageController::class, 'show']);
    Route::put('/{id}', [ImageController::class, 'update']);
});

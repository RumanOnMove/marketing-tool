<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:api'], 'prefix' => 'api/v1/email-templates'], function () {

});

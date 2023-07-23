<?php

namespace Moveon\EmailTemplate\Providers;

use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class EmailTemplateServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        Str::macro('replacePlaceholder', function ($originalStr, $placeholders) {
            foreach ($placeholders as $placeholder => $value) {
                $originalStr = Str::replace('{'.$placeholder.'}', $value, $originalStr);
            }
            return $originalStr;
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        Route::group(['prefix' => 'api', 'middleware' => [SubstituteBindings::class]], function () {
            $this->loadRoutesFrom(__DIR__ . '/../Routes/api.php');
        });

        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }
}

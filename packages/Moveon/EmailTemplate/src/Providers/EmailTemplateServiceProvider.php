<?php

namespace Moveon\EmailTemplate\Providers;

use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Moveon\EmailTemplate\Commands\FeatureTemplateSeedCommand;

class EmailTemplateServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->runningInConsole()) {
            $this->registerCommands();
        }

        Str::macro('replacePlaceholder', function ($originalStr, $placeholders) {
            foreach ($placeholders as $placeholder) {
                $originalStr = Str::replace(key($placeholder), $placeholder[key($placeholder)], $originalStr);
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

    /**
     * Register package commands
     * @return void
     */
    public function registerCommands(): void
    {
        $this->commands([
            FeatureTemplateSeedCommand::class
        ]);
    }
}

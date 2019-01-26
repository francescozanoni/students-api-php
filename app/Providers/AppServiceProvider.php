<?php

namespace App\Providers;

use HKarlstrom\OpenApiReader\OpenApiReader;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     */
    public function register()
    {

        $this->app->singleton('HKarlstrom\OpenApiReader\OpenApiReader', function ($app) {

            return new OpenApiReader($app['config']['openapi']['schema_file_path']);

        });

    }

}

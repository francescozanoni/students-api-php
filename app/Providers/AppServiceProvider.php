<?php

namespace App\Providers;

use HKarlstrom\OpenApiReader\OpenApiReader;
use Illuminate\Support\ServiceProvider;
use ReflectionObject;
use Respect\Validation\Rules\CountryCode;

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

        $this->app->bind('country_codes', function () {

            // Quick-n-dirty country code list retrieval, inspired by
            // https://stackoverflow.com/questions/2738663/call-private-methods-and-private-properties-from-outside-a-class-in-php
            $validator = new CountryCode();
            $reflector = new ReflectionObject($validator);
            $method = $reflector->getMethod('getCountryCodeList');
            $method->setAccessible(true);
            return $method->invoke($validator, 0);

            // @todo find a better way

        });

    }

}

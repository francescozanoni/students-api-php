<?php

namespace App\Providers;

use HKarlstrom\OpenApiReader\OpenApiReader;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Yaml\Yaml;

class AppServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

        $this->app->singleton('HKarlstrom\OpenApiReader\OpenApiReader', function ($app) {

            $tmpSchemaFilePath = sys_get_temp_dir() . '/' . date('YmdHis') . '.json';
            $schema = Yaml::parseFile($app['config']['openapi']['schema_file_path']);
            $schema = json_encode($schema, JSON_PARTIAL_OUTPUT_ON_ERROR);
            file_put_contents($tmpSchemaFilePath, $schema);

            $openApiReader = new OpenApiReader($tmpSchemaFilePath);

            unlink($tmpSchemaFilePath);

            return $openApiReader;

        });

    }

}

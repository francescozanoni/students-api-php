<?php

namespace App\Providers;

use HKarlstrom\OpenApiReader\OpenApiReader;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
    
        Validator::extend(
            'not_overlapping_time_range',
            /**
             * @param string @attribute name of the attribute being validated
             * @param mixed $value value of the attribute
             * @param array $parameters array of parameters passed to the rule
             * @param Validator @validator the Validator instance
             *
             * @return @bool
             */
            function ($attribute, $value, $parameters, $validator) {
            
                // $parameters: other time attribute, database table name, filter 1 field, filter 1 value
                $otherTimeAttribute = $parameters[0];
                $tableName = $parameters[1];
                $filters = [];
                if (isset($parameters[2]) === true &&
                    isset($parameters[3]) === true) {
                    $filters[$parameters[2]] = $parameters[3];
                }
                
                // @todo get other time atribute value
                // @todo sort time attributes
                
                $query = DB::table($tableName);
                if (empty($filters) === false) {
                    foreach ($filters as $k => $v) {
                        $query->where($k, $v);
                    }
                }
                
                // @todo add real time range search logic
                
                return $query->doesntExist();
                
            }
        );
        
    }

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

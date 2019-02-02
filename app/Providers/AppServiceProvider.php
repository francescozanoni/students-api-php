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
             * Validate a time range does not overlap time ranges on database table.
             *
             * @param string $attribute name of the attribute being validated
             * @param mixed $value value of the attribute
             * @param array $parameters array of parameters passed to the rule
             * @param Validator $validator the Validator instance
             *
             * @return @bool
             */
            function ($attribute, $value, $parameters, $validator) {
            
                // $parameters: other time attribute, database table name, filter 1 field, filter 1 value
                $otherTimeAttribute = $parameters[0];
                $otherTimeAttributeValue = $validator->getData()[$otherTimeAttribute];
                $tableName = $parameters[1];
                $filters = [];
                if (isset($parameters[2]) === true &&
                    isset($parameters[3]) === true) {
                    $filters[$parameters[2]] = $parameters[3];
                }
                
                $timeRange = [
                    'start' => min($value, $otherTimeAttributeValue),
                    'end' => max($value, $otherTimeAttributeValue),
                ];
                
                $query = DB::table($tableName);
                if (empty($filters) === false) {
                    foreach ($filters as $k => $v) {
                        $query->where($k, $v);
                    }
                }
                $query->where(function ($query) use ($timeRange) {
                    $query->whereRaw('? BETWEEN start_date AND end_date', [$timeRange['start']])
                        ->orWhereRaw('? BETWEEN start_date AND end_date', [$timeRange['end']])
                        ->orWhereRaw('start_date < ? AND end_date > ?', [$timeRange['start'], $timeRange['end']]);
                });
                
                // @todo make database field names dynamic
                
                // https://stackoverflow.com/questions/7581861/mysql-time-overlapping
                // SELECT *
                // FROM activities
                // WHERE (.$start. BETWEEN startTime AND endTime)
                //    OR (.$end. BETWEEN startTime AND endTime)
                //    OR (startTime < .$start. AND endTime > .$end.);
                
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

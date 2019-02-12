<?php
declare(strict_types = 1);

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class ValidationServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {

        Validator::extend(

            'not_overlapping_time_range',

            /**
             * Ensure input time range does not overlap time ranges on database table.
             *
             * Be aware:
             *  - attribute names must match database field names,
             *  - start and end times must not match: @todo constraint to be relaxed.
             *
             * @param string $attribute name of the attribute being validated, e.g. start_date
             * @param mixed $value value of the attribute, e.g. 2019-01-01
             * @param array $parameters array of parameters passed to the rule, e.g. end_date, stages, student_id, =, 1
             * @param Validator $validator the Validator instance
             *
             * @return bool
             */
            function ($attribute, $value, $parameters, $validator) {

                // $parameters:
                //  - other time attribute,
                //  - database table name,
                //  - (optional) filter 1 field,
                //  - (optional) filter 1 operator,
                //  - (optional) filter 1 value (no comma allowed),
                //  - (optional) filter 2 field,
                //  - (optional) filter 2 operator,
                //  - (optional) filter 2 value (no comma allowed).
                $otherTimeAttribute = array_shift($parameters);
                $otherTimeAttributeValue = $validator->getData()[$otherTimeAttribute];
                $tableName = array_shift($parameters);
                if ((count($parameters) % 3) !== 0) {
                    throw new \InvalidArgumentException('Filters must be passed as groups of three elements');
                }
                $filters = [];
                while (empty($parameters) === false) {
                    $filters[] = [
                        'field' => array_shift($parameters),
                        'operator' => array_shift($parameters),
                        'value' => array_shift($parameters),
                    ];
                }

                // Time values are sorted.
                $startTime = min($value, $otherTimeAttributeValue);
                $endTime = max($value, $otherTimeAttributeValue);

                // Time attribute names are sorted and sanitized, in order to correctly populate SQL query.
                $startField = preg_replace('/\W/', '', ($value === $startTime ? $attribute : $otherTimeAttribute));
                $endField = preg_replace('/\W/', '', ($value === $endTime ? $attribute : $otherTimeAttribute));

                $query = DB::table($tableName);
                if (empty($filters) === false) {
                    foreach ($filters as $filter) {
                        $query->where($filter['field'], $filter['operator'], $filter['value']);
                    }
                }
                $query->where(function ($query) use ($startTime, $endTime, $startField, $endField) {
                    // https://stackoverflow.com/questions/7581861/mysql-time-overlapping
                    $query->whereRaw('? BETWEEN ' . $startField . ' AND ' . $endField, [$startTime])
                        ->orWhereRaw('? BETWEEN ' . $startField . ' AND ' . $endField, [$endTime])
                        ->orWhereRaw($startField . ' <= ? AND ' . $endField . ' >= ?', [$startTime, $endTime]);
                });

                return $query->doesntExist();

            }

        );

        Validator::extend(
            'before_optional',
            /**
             * Validate a date (so far) to be before another date (so far), only if the latter is provided.
             *
             * @param string $attribute name of the attribute being validated, e.g. start_date
             * @param mixed $value value of the attribute, e.g. 2019-01-01
             * @param array $parameters array of parameters passed to the rule, e.g. end_date
             * @param Validator $validator the Validator instance
             *
             * @return bool
             */
            function ($attribute, $value, $parameters, $validator) {
                // @todo improve logic detecting date/time or field name
                if (preg_match('/^\d{4}\-\d{2}\-\d{2}$/', $parameters[0]) !== 1) {
                    if (isset($validator->getData()[$parameters[0]]) === false) {
                        return true;
                    }
                }
                return $validator->validateBefore($attribute, $value, $parameters);
            }
        );

    }

    /**
     * Register any application services.
     */
    public function register()
    {

    }

}

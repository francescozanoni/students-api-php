<?php
declare(strict_types = 1);

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Validator;

/**
 * Class ValidateRequest
 * @package App\Http\Middleware
 */
class ValidateRequest
{

    /**
     * Validate request
     *
     * @param $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {

        // @todo assess how to validate resource ID path parameter

        switch (app('current_route_alias')) {

            case 'getStudents':
            case 'getStudentById':
            case 'deleteStudentById':
                break;

            case 'createStudent':
                break;

            case 'updateStudentById':
                Validator::make(
                    $request->all(),
                    ['id' => 'in:' . app('resource_id_path_parameter')],
                    ['id.in' => 'The :attribute must be one of the following values: :values']
                )->validate();
                break;

            default:

        }

        $response = $next($request);

        return $response;

    }

}
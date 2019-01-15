<?php
declare(strict_types = 1);

namespace App\Http\Middleware;

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

        switch (app('current_route_alias')) {
            case 'getStudents':
            case 'createStudent':
            case 'getStudentById':
            case 'updateStudentById':
            case 'deleteStudentById':

                break;
            default:
        }
    
        $response = $next($request);

        return $response;
        
    }

}
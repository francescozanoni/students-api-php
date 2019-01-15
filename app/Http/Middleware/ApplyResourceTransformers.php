<?php
declare(strict_types = 1);

namespace App\Http\Middleware;

use App\Http\Resources\Student as StudentResource;

/**
 * Class ApplyTransformers
 * @package App\Http\Middleware
 */
class ApplyResourceTransformers
{

    /**
     * Add response metadata
     *
     * @param $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
    
        $response = $next($request);

        switch (app('current_route_alias')) {
            case 'getStudents':
                // $response->setContent(StudentResource::collection($response->original));
                break;
            case 'createStudent':
            case 'getStudentById':
            case 'updateStudentById':
                $response->setContent(new StudentResource($response->original));
                break;
            case 'deleteStudentById':
                break;
            default:
        }

        return $response;
        
    }

}
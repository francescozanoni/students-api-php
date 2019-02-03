<?php
declare(strict_types = 1);

namespace App\Http\Middleware;

use App\Http\Resources\Annotation as AnnotationResource;
use App\Http\Resources\Stage as StageResource;
use App\Http\Resources\Student as StudentResource;

/**
 * Class ApplyResourceTransformers
 * @package App\Http\Middleware
 */
class ApplyResourceTransformers
{

    /**
     * Add response metadata
     *
     * @param $request
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {

        $response = $next($request);

        if ($response->isSuccessful() === false) {
            return $response;
        }

        switch (app('current_route_alias')) {

            case 'getStudents':
                $response->setContent(StudentResource::collection($response->original));
                break;

            case 'createStudent':
            case 'getStudentById':
            case 'updateStudentById':
                $response->setContent(new StudentResource($response->original));
                break;

            case 'getAnnotations':
            case 'getStudentAnnotations':
                $response->setContent(AnnotationResource::collection($response->original));
                break;

            case 'getAnnotationById':
            case 'createStudentAnnotation':
            case 'updateAnnotationById':
                $response->setContent(new AnnotationResource($response->original));
                break;

            case 'getStages':
            case 'getStudentStages':
                $response->setContent(StageResource::collection($response->original));
                break;

            case 'getStageById':
            case 'createStudentStage':
            case 'updateStageById':
                $response->setContent(new StageResource($response->original));
                break;

            default:

        }

        return $response;

    }

}
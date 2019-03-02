<?php
declare(strict_types = 1);

namespace App\Http\Middleware;

use App\Http\Resources\Annotation as AnnotationResource;
use App\Http\Resources\EducationalActivityAttendance as EducationalActivityAttendanceResource;
use App\Http\Resources\Evaluation as EvaluationResource;
use App\Http\Resources\InterruptionReport as InterruptionReportResource;
use App\Http\Resources\SeminarAttendance as SeminarAttendanceResource;
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

            case 'getSeminarAttendances':
            case 'getStudentSeminarAttendances':
                $response->setContent(SeminarAttendanceResource::collection($response->original));
                break;

            case 'getSeminarAttendanceById':
            case 'createStudentSeminarAttendance':
            case 'updateSeminarAttendanceById':
                $response->setContent(new SeminarAttendanceResource($response->original));
                break;

            case 'getEducationalActivityAttendances':
            case 'getStudentEducationalActivityAttendances':
                $response->setContent(EducationalActivityAttendanceResource::collection($response->original));
                break;

            case 'getEducationalActivityAttendanceById':
            case 'createStudentEducationalActivityAttendance':
            case 'updateEducationalActivityAttendanceById':
                $response->setContent(new EducationalActivityAttendanceResource($response->original));
                break;

            case 'getEvaluations':
            case 'getStageEvaluations':
                $response->setContent(EvaluationResource::collection($response->original));
                break;

            case 'getEvaluationById':
            case 'createStageEvaluation':
            case 'updateEvaluationById':
                $response->setContent(new EvaluationResource($response->original));
                break;

            case 'getInterruptionReports':
            case 'getStageInterruptionReports':
                $response->setContent(InterruptionReportResource::collection($response->original));
                break;

            case 'getInterruptionReportsById':
            case 'createStageInterruptionReports':
            case 'updateInterruptionReportsById':
                $response->setContent(new InterruptionReportResource($response->original));
                break;

            default:

        }

        return $response;

    }

}
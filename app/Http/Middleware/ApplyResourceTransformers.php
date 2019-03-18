<?php
declare(strict_types = 1);

namespace App\Http\Middleware;

use App\Http\Resources\Annotation as AnnotationResource;
use App\Http\Resources\EducationalActivityAttendance as EducationalActivityAttendanceResource;
use App\Http\Resources\Evaluation as EvaluationResource;
use App\Http\Resources\InterruptionReport as InterruptionReportResource;
use App\Http\Resources\Internship as InternshipResource;
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

            case 'getInternships':
            case 'getStudentInternships':
                $response->setContent(InternshipResource::collection($response->original));
                break;

            case 'getInternshipById':
            case 'createStudentInternship':
            case 'updateInternshipById':
                $response->setContent(new InternshipResource($response->original));
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
                $response->setContent(EvaluationResource::collection($response->original));
                break;

            case 'getInternshipEvaluation':
            case 'getEvaluationById':
            case 'createInternshipEvaluation':
            case 'updateEvaluationById':
                $response->setContent(new EvaluationResource($response->original));
                break;

            case 'getInterruptionReports':
                $response->setContent(InterruptionReportResource::collection($response->original));
                break;

            case 'getInternshipInterruptionReport':
            case 'getInterruptionReportById':
            case 'createInternshipInterruptionReport':
            case 'updateInterruptionReportById':
                $response->setContent(new InterruptionReportResource($response->original));
                break;

            default:

        }

        return $response;

    }

}
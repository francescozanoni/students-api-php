<?php
declare(strict_types = 1);

namespace App\Http\Middleware;

use App\Http\Middleware\Traits\UsesOpenApiValidator;
use App\Http\Resources\Internship as InternshipResource;
use App\Models\EducationalActivityAttendance;
use App\Models\Eligibility;
use App\Models\Internship;
use App\Models\InterruptionReport;
use App\Models\OshCourseAttendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

/**
 * Class ValidateRequest
 * @package App\Http\Middleware
 */
class ValidateRequest
{

    use UsesOpenApiValidator;

    /**
     * Validate request
     *
     * @param Request $request
     * @param \Closure $next
     *
     * @return mixed
     *
     * @throws \Exception
     * @ throws ValidationException --> "@ throws" is disabled because ValidationException::withMessages() method's
     *                                  return type is not explicit within source code, therefore IDEs could complain
     *                                  with the following warning:
     *                                  "Exception 'ValidationException' is never thrown in the function"
     *
     * @todo find a way to refactor this VERY LONG method
     */
    public function handle(Request $request, \Closure $next)
    {

        // STEP 1: validation against OpenAPI schema

        $path = (string)app('current_route_path');
        $httpMethod = strtolower($request->getMethod());
        $pathParameters = app('current_route_path_parameters');
        $this->openApiValidator->validateRequest($request, $path, $httpMethod, $pathParameters);

        // -------------------------------------------------------------------------------------------------------------

        // STEP 2: validations not achievable by OpenAPI schema

        switch (app('current_route_alias')) {

            case 'createStudent':
            case 'updateStudentById':
                Validator::make(
                    $request->request->all(),
                    [
                        'e_mail' => 'email',
                        'nationality' => Rule::exists('countries', 'code')->where(function ($query) {
                            $query->whereNull('deleted_at');
                        }),
                    ],
                    [
                        'e_mail.email' => 'The :attribute must be a valid e-mail address',
                        'nationality.exists' => 'The :attribute must be a valid ISO 3166-1 alpha-2 country code',
                    ]
                )->validate();
                break;

            case 'createStudentAnnotation':
                break;

            case 'updateAnnotationById':
                break;

            case 'deleteAnnotationById':
                break;

            case 'createStudentInternship':
                Validator::make(
                    $request->request->all(),
                    [
                        'location' => Rule::exists('locations', 'name')->where(function ($query) {
                            $query->whereNull('deleted_at');
                        }),
                        'sub_location' => Rule::exists('sub_locations', 'name')->where(function ($query) {
                            $query->whereNull('deleted_at');
                        }),
                        'start_date' => [
                            'bail',
                            'before:end_date',
                            'not_overlapping_time_range:end_date,internships,student_id,=,' . app('current_route_path_parameters')['id'] . ',deleted_at,is,null',
                        ],
                        'end_date' => [
                            'bail',
                            'after:start_date',
                            'not_overlapping_time_range:start_date,internships,student_id,=,' . app('current_route_path_parameters')['id'] . ',deleted_at,is,null',
                        ],
                    ],
                    [
                        'location.exists' => 'The :attribute must be a valid location',
                        'sub_location.exists' => 'The :attribute must be a valid sub-location',
                        'start_date.before' => 'The :attribute must be a date before end date',
                        'end_date.after' => 'The :attribute must be a date after start date',
                        'start_date.not_overlapping_time_range' => 'Unavailable time range',
                        'end_date.not_overlapping_time_range' => 'Unavailable time range',
                    ]
                )->validate();
                break;

            case 'updateInternshipById':
                $internship = Internship::find(app('current_route_path_parameters')['id']);
                if ($internship) {
                    // If attribute "is_interrupted" is switched from true to false,
                    // there must not be any interruption reports.
                    if ((new InternshipResource($internship))->toArray($request)['is_interrupted'] === true &&
                        $request->request->get('is_interrupted') === false &&
                        $internship->interruptionReport !== null) {
                        throw ValidationException::withMessages(['is_interrupted' => ['Internship actually has interruption report']]);
                    }
                    Validator::make(
                        $request->request->all(),
                        [
                            'location' => Rule::exists('locations', 'name')->where(function ($query) {
                                $query->whereNull('deleted_at');
                            }),
                            'sub_location' => Rule::exists('sub_locations', 'name')->where(function ($query) {
                                $query->whereNull('deleted_at');
                            }),
                            'start_date' => [
                                'bail',
                                'before:end_date',
                                'not_overlapping_time_range:end_date,internships,student_id,=,' . $internship->student->id . ',id,!=,' . $internship->id . ',deleted_at,is,null',
                            ],
                            'end_date' => [
                                'bail',
                                'after:start_date',
                                'not_overlapping_time_range:start_date,internships,student_id,=,' . $internship->student->id . ',id,!=,' . $internship->id . ',deleted_at,is,null',
                            ],
                        ],
                        [
                            'location.exists' => 'The :attribute must be a valid location',
                            'sub_location.exists' => 'The :attribute must be a valid sub-location',
                            'start_date.before' => 'The :attribute must be a date before end date',
                            'end_date.after' => 'The :attribute must be a date after start date',
                            'start_date.not_overlapping_time_range' => 'Unavailable time range',
                            'end_date.not_overlapping_time_range' => 'Unavailable time range',
                        ]
                    )->validate();
                }
                break;

            case 'deleteInternshipById':
                $internship = Internship::find(app('current_route_path_parameters')['id']);
                if ($internship) {
                    // In case of evaluation or interruption report available, internship cannot be deleted.
                    if ($internship->evaluation !== null ||
                        $internship->interruptionReport !== null) {
                        throw ValidationException::withMessages(['internship_id' => ['Internship actually has evaluation and/or interruption report']]);
                    }
                }
                break;

            case 'createStudentEducationalActivityAttendance':
                Validator::make(
                    $request->request->all(),
                    [
                        'educational_activity' => [
                            // Student/educational activity/start date uniqueness
                            Rule::unique('educational_activity_attendances')
                                ->where(function ($query) use ($request) {
                                    return $query
                                        ->where('student_id', app('current_route_path_parameters')['id'])
                                        ->where('start_date', $request->request->get('start_date'));
                                }),
                        ],
                        'start_date' => [
                            'bail',
                            'before_optional:end_date',
                            // Student/educational activity/start date uniqueness
                            Rule::unique('educational_activity_attendances')
                                ->where(function ($query) use ($request) {
                                    return $query
                                        ->where('student_id', app('current_route_path_parameters')['id'])
                                        ->where('educational_activity', $request->request->get('educational_activity'));
                                }),
                        ],
                        'end_date' => [
                            'bail',
                            'after:start_date',
                        ],
                    ],
                    [
                        'educational_activity.unique' => 'Combination of student, educational activity and start date already used',
                        'start_date.unique' => 'Combination of student, educational activity and start date already used',
                        'start_date.before_optional' => 'The :attribute must be a date before end date',
                        'end_date.after' => 'The :attribute must be a date after start date',
                    ]
                )->validate();
                break;

            case 'updateEducationalActivityAttendanceById':
                $educationalActivityAttendance = EducationalActivityAttendance::find(app('current_route_path_parameters')['id']);
                if ($educationalActivityAttendance) {
                    Validator::make(
                        $request->request->all(),
                        [
                            'educational_activity' => [
                                // Student/educational activity/start date uniqueness
                                Rule::unique('educational_activity_attendances')
                                    ->where(function ($query) use ($request, $educationalActivityAttendance) {
                                        return $query
                                            ->where('student_id', $educationalActivityAttendance->student->id)
                                            ->where('start_date', $request->request->get('start_date'));
                                    })
                                    ->ignore($educationalActivityAttendance->id),
                            ],
                            'start_date' => [
                                'bail',
                                'before_optional:end_date',
                                // Student/educational activity/start date uniqueness
                                Rule::unique('educational_activity_attendances')
                                    ->where(function ($query) use ($request, $educationalActivityAttendance) {
                                        return $query
                                            ->where('student_id', $educationalActivityAttendance->student->id)
                                            ->where('educational_activity', $request->request->get('educational_activity'));
                                    })
                                    ->ignore($educationalActivityAttendance->id),
                            ],
                            'end_date' => [
                                'bail',
                                'after:start_date',
                            ],
                        ],
                        [
                            'educational_activity.unique' => 'Combination of student, educational activity and start date already used',
                            'start_date.unique' => 'Combination of student, educational activity and start date already used',
                            'start_date.before_optional' => 'The :attribute must be a date before end date',
                            'end_date.after' => 'The :attribute must be a date after start date',
                        ]
                    )->validate();
                }
                break;

            case 'createInternshipEvaluation':
                $internship = Internship::find(app('current_route_path_parameters')['id']);
                if ($internship) {
                    // Internship must not have any evaluations yet.
                    if ($internship->evaluation !== null) {
                        throw ValidationException::withMessages(['internship_id' => ['Internship already has evaluation']]);
                    }
                    // Internship must be already started.
                    $currentDate = new \DateTime();
                    $internshipStartDate = \DateTime::createFromFormat('Y-m-d', $internship->start_date);
                    if ((int)($currentDate->diff($internshipStartDate))->format('%r%d') > 1) {
                        throw ValidationException::withMessages(['internship_id' => ['Internship not started yet']]);
                    }
                }
                break;

            case 'updateEvaluationById':
                break;

            case 'createInternshipInterruptionReport':
                $internship = Internship::find(app('current_route_path_parameters')['id']);
                if ($internship) {
                    // Internship must be interrupted.
                    if ((new InternshipResource($internship))->toArray($request)['is_interrupted'] !== true) {
                        throw ValidationException::withMessages(['internship_id' => ['Internship is not interrupted']]);
                    }
                    // Internship must not have any interruption reports yet.
                    if ($internship->interruptionReport !== null) {
                        throw ValidationException::withMessages(['internship_id' => ['Internship already has interruption report']]);
                    }
                    // Internship must be already started.
                    $currentDate = new \DateTime();
                    $internshipStartDate = \DateTime::createFromFormat('Y-m-d', $internship->start_date);
                    if ((int)($currentDate->diff($internshipStartDate))->format('%r%d') > 1) {
                        throw ValidationException::withMessages(['internship_id' => ['Internship not started yet']]);
                    }
                }
                break;

            case 'updateInterruptionReportById':
                $interruptionReport = InterruptionReport::find(app('current_route_path_parameters')['id']);
                if ($interruptionReport) {
                    // Internship must be interrupted.
                    if ((new InternshipResource($interruptionReport->internship))->toArray($request)['is_interrupted'] !== true) {
                        throw ValidationException::withMessages(['internship_id' => ['Internship is not interrupted']]);
                    }
                }
                break;

            case 'createStudentEligibility':
                Validator::make(
                    $request->request->all(),
                    [
                        'start_date' => [
                            'bail',
                            'before:end_date',
                            'not_overlapping_time_range:end_date,eligibilities,student_id,=,' . app('current_route_path_parameters')['id'] . ',deleted_at,is,null',
                        ],
                        'end_date' => [
                            'bail',
                            'after:start_date',
                            'not_overlapping_time_range:start_date,eligibilities,student_id,=,' . app('current_route_path_parameters')['id'] . ',deleted_at,is,null',
                        ],
                    ],
                    [
                        'start_date.before' => 'The :attribute must be a date before end date',
                        'end_date.after' => 'The :attribute must be a date after start date',
                        'start_date.not_overlapping_time_range' => 'Unavailable time range',
                        'end_date.not_overlapping_time_range' => 'Unavailable time range',
                    ]
                )->validate();
                break;

            case 'updateEligibilityById':
                $eligibility = Eligibility::find(app('current_route_path_parameters')['id']);
                if ($eligibility) {
                    // @todo handle case of switch of is_eligible from true to false, with related internships, if its config parameter is true
                    // @todo handle case of switch of date change, with related internships, if its config parameter is true
                    Validator::make(
                        $request->request->all(),
                        [
                            'start_date' => [
                                'bail',
                                'before:end_date',
                                'not_overlapping_time_range:end_date,eligibilities,student_id,=,' . $eligibility->student->id . ',id,!=,' . $eligibility->id . ',deleted_at,is,null',
                            ],
                            'end_date' => [
                                'bail',
                                'after:start_date',
                                'not_overlapping_time_range:start_date,eligibilities,student_id,=,' . $eligibility->student->id . ',id,!=,' . $eligibility->id . ',deleted_at,is,null',
                            ],
                        ],
                        [
                            'start_date.before' => 'The :attribute must be a date before end date',
                            'end_date.after' => 'The :attribute must be a date after start date',
                            'start_date.not_overlapping_time_range' => 'Unavailable time range',
                            'end_date.not_overlapping_time_range' => 'Unavailable time range',
                        ]
                    )->validate();
                }
                break;

            case 'deleteEligibilityById':
                // @todo handle case of eligibility with related internships, if its config parameter is true
                break;

            case 'createStudentOshCourseAttendance':
                Validator::make(
                    $request->request->all(),
                    [
                        'start_date' => [
                            'bail',
                            'before:end_date',
                            'not_overlapping_time_range:end_date,osh_course_attendances,student_id,=,' . app('current_route_path_parameters')['id'] . ',deleted_at,is,null',
                        ],
                        'end_date' => [
                            'bail',
                            'after:start_date',
                            'not_overlapping_time_range:start_date,osh_course_attendances,student_id,=,' . app('current_route_path_parameters')['id'] . ',deleted_at,is,null',
                        ],
                    ],
                    [
                        'start_date.before' => 'The :attribute must be a date before end date',
                        'end_date.after' => 'The :attribute must be a date after start date',
                        'start_date.not_overlapping_time_range' => 'Unavailable time range',
                        'end_date.not_overlapping_time_range' => 'Unavailable time range',
                    ]
                )->validate();
                break;

            case 'updateOshCourseAttendanceById':
                $oshCourseAttendance = OshCourseAttendance::find(app('current_route_path_parameters')['id']);
                if ($oshCourseAttendance) {
                    // @todo handle case of date change, with related internships, if its config parameter is true
                    Validator::make(
                        $request->request->all(),
                        [
                            'start_date' => [
                                'bail',
                                'before:end_date',
                                'not_overlapping_time_range:end_date,osh_course_attendances,student_id,=,' . $oshCourseAttendance->student->id . ',id,!=,' . $oshCourseAttendance->id . ',deleted_at,is,null',
                            ],
                            'end_date' => [
                                'bail',
                                'after:start_date',
                                'not_overlapping_time_range:start_date,osh_course_attendances,student_id,=,' . $oshCourseAttendance->student->id . ',id,!=,' . $oshCourseAttendance->id . ',deleted_at,is,null',
                            ],
                        ],
                        [
                            'start_date.before' => 'The :attribute must be a date before end date',
                            'end_date.after' => 'The :attribute must be a date after start date',
                            'start_date.not_overlapping_time_range' => 'Unavailable time range',
                            'end_date.not_overlapping_time_range' => 'Unavailable time range',
                        ]
                    )->validate();
                }
                break;

            case 'deleteOshCourseAttendanceById':
                // @todo handle case of attendances with related internships, if its config parameter is true
                break;

            default:

        }

        // -------------------------------------------------------------------------------------------------------------

        // PATCH: in case of creation/modification of a resource/property, body is required,
        //        but package hkarlstrom/openapi-validation-middleware does not handle empty body correctly,
        //        therefore the following additional check is required.
        if (preg_match('/^(create|update)/', app('current_route_alias')) === 1 &&
            count($request->request) === 0) {
            throw ValidationException::withMessages(['requestBody' => ['code error_required']]);
        }

        // -------------------------------------------------------------------------------------------------------------

        $response = $next($request);

        return $response;

    }

}
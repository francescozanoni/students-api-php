<?php
declare(strict_types = 1);

namespace App\Http\Middleware;

use App\Http\Middleware\Traits\UsesOpenApiValidator;
use App\Models\Annotation;
use App\Models\EducationalActivityAttendance;
use App\Models\Evaluation;
use App\Models\InterruptionReport;
use App\Models\SeminarAttendance;
use App\Models\Stage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

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
     * @param $request
     * @param \Closure $next
     *
     * @return mixed
     *
     * @throws \Exception
     * @ throws ValidationException --> "@ throws" is disabled because ValidationException::withMessages() method's
     *                                  return type is not explicit within source code, therefore IDEs could complain
     *                                  with the following warning:
     *                                  "Exception 'ValidationException' is never thrown in the function"
     */
    public function handle($request, \Closure $next)
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
                /* @todo validate user_id against users table
                 * Validator::make(
                 *   $request->request->all(),
                 *   ['user_id' => 'exists:users,id'],
                 *   ['user_id.exists' => 'The :attribute must exist']
                 * )->validate();
                 */
                break;

            case 'updateAnnotationById':
                // An annotation cannot be changed by a different user.
                $annotation = Annotation::find(app('current_route_path_parameters')['id']);
                if ($annotation) {
                    Validator::make(
                        $request->request->all(),
                        [
                            'user_id' => 'in:' . $annotation->user_id,
                        ],
                        [
                            'user_id.in' => 'The :attribute cannot be changed',
                        ]
                    )->validate();
                }
                break;

            case 'deleteAnnotationById':
                // @todo add user_id validation, that must be provided and match the current value
                break;

            case 'createStudentStage':
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
                            'not_overlapping_time_range:end_date,stages,student_id,=,' . app('current_route_path_parameters')['id'],
                        ],
                        'end_date' => [
                            'bail',
                            'after:start_date',
                            'not_overlapping_time_range:start_date,stages,student_id,=,' . app('current_route_path_parameters')['id'],
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

            case 'updateStageById':
                $stage = Stage::find(app('current_route_path_parameters')['id']);
                if ($stage) {
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
                                'not_overlapping_time_range:end_date,stages,student_id,=,' . $stage->student->id . ',id,!=,' . $stage->id,
                            ],
                            'end_date' => [
                                'bail',
                                'after:start_date',
                                'not_overlapping_time_range:start_date,stages,student_id,=,' . $stage->student->id . ',id,!=,' . $stage->id,
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
                    // @todo if "is_interrupted" is switched from true to false, there must not be an interruption report
                }
                break;
                
            case 'deleteStageById':
                $stage = Stage::find(app('current_route_path_parameters')['id']);
                if ($stage) {
                    // @todo block deletion in case of evaluation or interruption report available
                }
                break;

            case 'createStudentSeminarAttendance':
                Validator::make(
                    $request->request->all(),
                    [
                        'seminar' => [
                            // Student/seminar/start date uniqueness
                            Rule::unique('seminar_attendances')
                                ->where(function ($query) use ($request) {
                                    return $query
                                        ->where('student_id', app('current_route_path_parameters')['id'])
                                        ->where('start_date', $request->request->get('start_date'));
                                }),
                        ],
                        'start_date' => [
                            'bail',
                            'before_optional:end_date',
                            // Student/seminar/start date uniqueness
                            Rule::unique('seminar_attendances')
                                ->where(function ($query) use ($request) {
                                    return $query
                                        ->where('student_id', app('current_route_path_parameters')['id'])
                                        ->where('seminar', $request->request->get('seminar'));
                                }),
                        ],
                        'end_date' => [
                            'bail',
                            'after:start_date',
                        ],
                    ],
                    [
                        'seminar.unique' => 'Combination of student, seminar and start date already used',
                        'start_date.unique' => 'Combination of student, seminar and start date already used',
                        'start_date.before_optional' => 'The :attribute must be a date before end date',
                        'end_date.after' => 'The :attribute must be a date after start date',
                    ]
                )->validate();
                break;

            case 'updateSeminarAttendanceById':
                $educationalActivityAttendance = SeminarAttendance::find(app('current_route_path_parameters')['id']);
                if ($educationalActivityAttendance) {
                    Validator::make(
                        $request->request->all(),
                        [
                            'seminar' => [
                                // Student/seminar/start date uniqueness
                                Rule::unique('seminar_attendances')
                                    ->where(function ($query) use ($request, $educationalActivityAttendance) {
                                        return $query
                                            ->where('student_id', $educationalActivityAttendance->student->id)
                                            ->where('start_date', $request->request->get('start_date'));
                                    }),
                            ],
                            'start_date' => [
                                'bail',
                                'before_optional:end_date',
                                // Student/seminar/start date uniqueness
                                Rule::unique('seminar_attendances')
                                    ->where(function ($query) use ($request, $educationalActivityAttendance) {
                                        return $query
                                            ->where('student_id', $educationalActivityAttendance->student->id)
                                            ->where('seminar', $request->request->get('seminar'));
                                    }),
                            ],
                            'end_date' => [
                                'bail',
                                'after:start_date',
                            ],
                        ],
                        [
                            'seminar.unique' => 'Combination of student, seminar and start date already used',
                            'start_date.unique' => 'Combination of student, seminar and start date already used',
                            'start_date.before_optional' => 'The :attribute must be a date before end date',
                            'end_date.after' => 'The :attribute must be a date after start date',
                        ]
                    )->validate();
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
                                    }),
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
                }
                break;

            case 'createStageEvaluation':
                Validator::make(
                    $request->request->all(),
                    [
                    ],
                    [
                    ]
                )->validate();
                // @todo current date must be after stage's start date
                break;

            case 'updateEvaluationById':
                $evaluation = Evaluation::find(app('current_route_path_parameters')['id']);
                if ($evaluation) {
                    Validator::make(
                        $request->request->all(),
                        [
                        ],
                        [
                        ]
                    )->validate();
                }
                break;

            case 'createStageInterruptionReport':
                Validator::make(
                    $request->request->all(),
                    [
                    ],
                    [
                    ]
                )->validate();
                // @todo stage must be interrupted
                // @todo current date must be after stage's start date
                break;

            case 'updateInterruptionReportById':
                $interruptionReport = InterruptionReport::find(app('current_route_path_parameters')['id']);
                if ($interruptionReport) {
                    Validator::make(
                        $request->request->all(),
                        [
                        ],
                        [
                        ]
                    )->validate();
                    // @todo stage must be interrupted
                }
                break;

            default:

        }

        // -------------------------------------------------------------------------------------------------------------

        $response = $next($request);

        return $response;

    }

}
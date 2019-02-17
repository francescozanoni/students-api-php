<?php
declare(strict_types = 1);

namespace App\Http\Middleware;

use App\Models\Annotation;
use App\Models\Stage;
use App\Services\OpenApiValidator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

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
     *
     * @return mixed
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function handle($request, \Closure $next)
    {

        // STEP 1: validation against OpenAPI schema

        $validator = new OpenApiValidator(config('openapi.schema_file_path'));
        $path = (string)app('current_route_path');
        $httpMethod = strtolower($request->getMethod());
        $pathParameters = app('current_route_path_parameters');
        $validator->validateRequest($request, $path, $httpMethod, $pathParameters);

        // @todo add validation of keys: keys not described by schema must not be accepted

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
                /* @todo validate user_id against users table of another database/application
                 * Validator::make(
                 *   $request->request->all(),
                 *   ['user_id' => 'exists:other_sqlite.users,id'],
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
                }
                break;

            case 'createStudentSeminarAttendance':
                Validator::make(
                    $request->request->all(),
                    [
                        'start_date' => [
                            'bail',
                            'before_optional:end_date',
                        ],
                        'end_date' => [
                            'bail',
                            'after:start_date',
                        ],
                    ],
                    [
                        'start_date.before_optional' => 'The :attribute must be a date before end date',
                        'end_date.after' => 'The :attribute must be a date after start date',
                    ]
                )->validate();
                // @todo add seminar/student/start date uniqueness check
                break;

            case 'updateSeminarAttendanceById':
                $seminarAttendance = Stage::find(app('current_route_path_parameters')['id']);
                if ($seminarAttendance) {
                    Validator::make(
                        $request->request->all(),
                        [
                            'start_date' => [
                                'bail',
                                'before_optional:end_date',
                            ],
                            'end_date' => [
                                'bail',
                                'after:start_date',
                            ],
                        ],
                        [
                            'start_date.before_optional' => 'The :attribute must be a date before end date',
                            'end_date.after' => 'The :attribute must be a date after start date',
                        ]
                    )->validate();
                    // @todo add seminar/student/start date uniqueness check
                }
                break;

            default:

        }

        // -------------------------------------------------------------------------------------------------------------

        $response = $next($request);

        return $response;

    }

}
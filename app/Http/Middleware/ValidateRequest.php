<?php
declare(strict_types = 1);

namespace App\Http\Middleware;

use App\Models\Annotation;
use App\Services\OpenApiValidator;
use Illuminate\Support\Facades\Validator;
use Respect\Validation\Exceptions\ValidationException as OpenApiValidationException;
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
     * @throws \Exception
     */
    public function handle($request, \Closure $next)
    {

        // STEP 1: validation against OpenAPI schema

        $validator = new OpenApiValidator(config('openapi.schema_file_path'));
        $errors = $validator->validateRequest($request);
        if (empty($errors) === false) {
            $errors = json_encode($errors, JSON_PARTIAL_OUTPUT_ON_ERROR | JSON_UNESCAPED_SLASHES);
            throw new OpenApiValidationException($errors);
        }

        // @todo add validation of keys: keys not described by schema must not be accepted

        // -------------------------------------------------------------------------------------------------------------

        // STEP 2: validations not achievable by OpenAPI schema

        switch (app('current_route_alias')) {

            case 'getStudents':
            case 'getStudentById':
            case 'deleteStudentById':
                break;

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
                        'nationality.exists' => 'The :attribute must be a valid ISO 3166-1 alpha-2 country code'
                    ]
                )->validate();
                break;

            case 'createStudentAnnotation':
                /* @todo validate user_id against users table of another database/application
                Validator::make(
                    $request->request->all(),
                    [
                        'user_id' => 'exists:other_sqlite.users,id',
                    ],
                    [
                        'user_id.exists' => 'The :attribute must exist',
                    ]
                )->validate();
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

            default:

        }

        // -------------------------------------------------------------------------------------------------------------

        $response = $next($request);

        return $response;

    }

}
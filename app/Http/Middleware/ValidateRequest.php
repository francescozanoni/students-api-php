<?php
declare(strict_types = 1);

namespace App\Http\Middleware;

use App\Services\OpenApiValidator;
use Illuminate\Support\Facades\Validator;
use Respect\Validation\Exceptions\ValidationException as OpenApiValidationException;


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

        // -------------------------------------------------------------------------------------------------------------

        // STEP 2: validations not achievable by OpenAPI schema

        switch (app('current_route_alias')) {

            case 'getStudents':
            case 'getStudentById':
            case 'deleteStudentById':
                break;

            case 'createStudent':
                // @todo add country_code validation
                break;

            case 'updateStudentById':
                // @todo add country_code validation
                break;

            default:

        }

        $response = $next($request);

        return $response;

    }

}
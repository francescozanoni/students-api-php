<?php
declare(strict_types = 1);

namespace App\Http\Middleware;

use HKarlstrom\Middleware\OpenApiValidation;
use Illuminate\Support\Facades\Validator;
use ReflectionObject;
use Respect\Validation\Exceptions\ValidationException as OpenApiValidationException;
use Symfony\Component\Yaml\Yaml;


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
     *
     * @throws \Exception
     */
    public function handle($request, \Closure $next)
    {

        $psr7Request = app('psr7_request');
        $path = (string)app('current_route_path');
        $httpMethod = strtolower($request->method());
        $pathParameters = $request->route()[2] ?? [];

        $schema = Yaml::parseFile(config('openapi.schema_file_path'));
        $schema = json_encode($schema, JSON_PRETTY_PRINT | JSON_PARTIAL_OUTPUT_ON_ERROR | JSON_UNESCAPED_SLASHES);
        file_put_contents(storage_path('app/aaaad.json'), $schema);
        $validator = new OpenApiValidation(storage_path('app/aaaad.json'));

        // OpenApiValidation->validateRequest() is private, therefore Reflection must be used to access it.
        // https://stackoverflow.com/questions/2738663/call-private-methods-and-private-properties-from-outside-a-class-in-php
        // https://stackoverflow.com/questions/26133863/why-does-passing-a-variable-by-reference-not-work-when-invoking-a-reflective-met
        $reflector = new ReflectionObject($validator);
        $method = $reflector->getMethod('validateRequest');
        $method->setAccessible(true);
        $errors = $method->invokeArgs($validator, [&$psr7Request, $path, $httpMethod, $pathParameters]);

        if (empty($errors) === false) {
            throw new OpenApiValidationException(json_encode($errors, JSON_PRETTY_PRINT | JSON_PARTIAL_OUTPUT_ON_ERROR | JSON_UNESCAPED_SLASHES));
        }

        switch (app('current_route_alias')) {

            case 'getStudents':
            case 'getStudentById':
            case 'deleteStudentById':
                break;

            case 'createStudent':
                break;

            case 'updateStudentById':
                Validator::make(
                    $request->all(),
                    ['id' => 'in:' . app('resource_id_path_parameter')],
                    ['id.in' => 'The :attribute must be one of the following values: :values']
                )->validate();
                break;

            default:

        }

        $response = $next($request);

        return $response;

    }

}
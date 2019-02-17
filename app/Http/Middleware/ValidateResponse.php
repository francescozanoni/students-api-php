<?php
declare(strict_types = 1);

namespace App\Http\Middleware;

use App\Services\OpenApiValidator;

/**
 * Class ValidateResponse
 * @package App\Http\Middleware
 */
class ValidateResponse
{

    /**
     * Validate response
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

        $response = $next($request);

        // Root route is not validated, since it only redirects to Swagger UI application.
        if (app('current_route_alias') === 'root') {
            return $response;
        }

        $path = (string)app('current_route_path');
        $httpMethod = strtolower($request->getMethod());

        $validator = new OpenApiValidator(config('openapi.schema_file_path'));
        $validator->validateResponse($response, $path, $httpMethod);

        return $response;

    }

}
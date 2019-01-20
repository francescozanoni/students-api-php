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
     * @throws \Exception
     */
    public function handle($request, \Closure $next)
    {

        $response = $next($request);

        /* This middleware cannot be used until hkarlstrom/openapi-validation-middleware does not support
         * allOf in any part of the schema (planned enhancement), therefore immediately return $response;,
         * otherwise the following error occurs:
         *
         * {
         *   "status_code": 500,
         *   "status": "Internal Server Error",
         *   "message": "An internal server error occurred",
         *   "data": [
         *     {
         *       "name": "data.1",
         *       "code": "error_allOf",
         *       "value": {
         *         "id": 2,
         *         "first_name": "Jane",
         *         "last_name": "Doe",
         *         "e_mail": "jane.doe@bar.com",
         *         "phone": null,
         *         "nationality": "CA"
         *       }
         *     }
         *   ]
         * }
         */
        return $response;

        $path = (string)app('current_route_path');
        $httpMethod = strtolower($request->getMethod());

        $validator = new OpenApiValidator(config('openapi.schema_file_path'));
        $errors = $validator->validateResponse($response, $path, $httpMethod);

        if (empty($errors) === false) {
            // Since AddResponseMetadata and PrettyPrint middlewares have already been executed,
            // their logic is here re-applied manually on the error response.
            // @todo improve design of this
            $response->setStatusCode(500);
            $metadata = app('App\Http\Middleware\AddResponseMetadata')->getMetadata($request, $response);
            $fullData = array_merge($metadata, ['data' => $errors]);
            $response->setContent(json_encode($fullData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        }

        return $response;

    }

}
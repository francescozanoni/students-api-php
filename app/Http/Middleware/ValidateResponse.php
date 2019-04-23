<?php
declare(strict_types = 1);

namespace App\Http\Middleware;

use App\Http\Middleware\Traits\UsesOpenApiValidator;
use Illuminate\Validation\ValidationException;

/**
 * Class ValidateResponse
 * @package App\Http\Middleware
 */
class ValidateResponse
{

    use UsesOpenApiValidator;

    /**
     * Validate response
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

        $response = $next($request);

        // Root route is not validated, since it only redirects to Swagger UI application.
        if (app('current_route_alias') === 'root') {
            return $response;
        }

        $path = (string)app('current_route_path');
        $httpMethod = strtolower($request->getMethod());

        try {
            $this->openApiValidator->validateResponse($response, $path, $httpMethod);
        } catch (ValidationException $e) {

            $response = new \Illuminate\Http\JsonResponse(null, 500);

            // Since AddResponseMetadata and PrettyPrint middlewares are not executed,
            // their logic is here re-applied manually on the error response.
            $metadata = app('App\Services\OpenApi\MetadataManager')->getMetadata($request, $response);
            $fullData = array_merge($metadata, ['data' => $e->validator->errors()->toArray()]);
            $response->setData($fullData);
            // https://www.aaronsaray.com/2017/laravel-pretty-print-middleware
            $response->setEncodingOptions(config('app.json_encode_options'));

        }

        return $response;

    }

}
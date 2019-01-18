<?php
declare(strict_types = 1);

namespace App\Http\Middleware;

use Illuminate\Validation\ValidationException;
use Respect\Validation\Exceptions\ValidationException as OpenApiValidationException;

/**
 * Class HandleNegativeResponseContent
 * @package App\Http\Middleware
 */
class HandleNegativeResponseContent
{

    /**
     * Handle responses reporting negative results.
     *
     * @param $request
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {

        $response = $next($request);

        // In case of Lumen validation errors, set messages as response content and 400 as status code.
        if (isset($response->exception) &&
            $response->exception instanceof ValidationException) {
            $response->setContent($response->exception->validator->errors()->toArray());
            $response->setStatusCode(400);
        }

        // In case of OpenAPI validation errors, set messages as response content and 400 as status code.
        if (isset($response->exception) &&
            $response->exception instanceof OpenApiValidationException) {
            $originalErrors = json_decode($response->exception->getMessage(), true);
            $errors = [];
            // @todo refactor OpenApiValidationException output conversion to Laravel/lumen validation error messages
            foreach ($originalErrors as $originalError) {
                $name = $originalError['name'];
                unset($originalError['name']);
                $errors[$name] = [];
                foreach ($originalError as $key => $value) {
                    $errors[$name][] = $key . ' ' . $value;
                }
            }
            $response->setContent($errors);
            $response->setStatusCode(400);
        }

        // In case of NOT FOUND, remove any content.
        if ($response->isNotFound() === true) {
            $response->setContent('');
        }

        // In case of server error (500 status code, typically),
        // JSON-ized light exception information is stored to response content.
        if ($response->isServerError() === true &&
            isset($response->exception)) {
            $response->setContent(
                json_encode(
                    [
                        'class' => get_class($response->exception),
                        'message' => $response->exception->getMessage(),
                        'file' => $response->exception->getFile(),
                        'line' => $response->exception->getLine(),
                        //'trace' => $response->exception->getTrace(),
                    ],
                    JSON_PARTIAL_OUTPUT_ON_ERROR | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
                )
            );
        }

        return $response;

    }

}
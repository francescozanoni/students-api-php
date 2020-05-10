<?php
declare(strict_types = 1);

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

/**
 * Class HandleNegativeResponseContent
 * @package App\Http\Middleware
 */
class HandleNegativeResponseContent
{

    /**
     * Handle responses reporting negative results.
     *
     * @param Request $request
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle(Request $request, \Closure $next)
    {

        $response = $next($request);

        // In case of Lumen validation errors, set messages as response content and 400 as status code.
        if (isset($response->exception) &&
            $response->exception instanceof ValidationException) {
            $response->setContent($response->exception->validator->errors()->toArray());
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
                    config('app.json_encode_options')
                )
            );
        }

        return $response;

    }

}
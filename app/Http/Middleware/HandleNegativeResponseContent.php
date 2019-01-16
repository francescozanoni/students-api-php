<?php
declare(strict_types = 1);

namespace App\Http\Middleware;

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
     * @param $request
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {

        $response = $next($request);

        // Set failed validation messages as response content and 400 as status code.
        if ($response->exception &&
            $response->exception instanceof ValidationException) {
            $response->setContent($response->exception->validator->errors()->toArray());
            $response->setStatusCode(400);
        }

        // In case of NOT FOUND, remove any content.
        if ($response->isNotFound() === true) {
            $response->setContent('');
        }

        return $response;

    }

}
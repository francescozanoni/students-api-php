<?php
declare(strict_types = 1);

namespace App\Http\Middleware;

use Illuminate\Validation\ValidationException;

/**
 * Class SetFailedValidationMessages
 * @package App\Http\Middleware
 */
class SetFailedValidationMessages
{

    /**
     * Set failed validation messages as response content.
     *
     * @param $request
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {

        $response = $next($request);

        if ($response->exception &&
            $response->exception instanceof ValidationException) {
            $response->setContent($response->exception->validator->errors()->toArray());
            $response->setStatusCode(400);
        }

        return $response;

    }

}
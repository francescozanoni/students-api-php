<?php
declare(strict_types = 1);

namespace App\Http\Middleware;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

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
     */
    public function handle($request, \Closure $next)
    {

        $response = $next($request);

        return $response;
        
    }

}
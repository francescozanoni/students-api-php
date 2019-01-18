<?php
declare(strict_types = 1);

namespace App\Http\Middleware;

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
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
    
        $response = $next($request);

        return $response;
        
    }

}
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
    
        // THIS MIDDLEWARE CANNOT BE GLOBAL,
        // OTHERWISE CURRENT ROUTE IS NOT AVAILABLE YET.
    
        if (isset(app('request')->route()[1]) === true &&
            isset(app('request')->route()[1]['as']) === true) {
        
        }
    
        $response = $next($request);

        return $response;
        
    }

}
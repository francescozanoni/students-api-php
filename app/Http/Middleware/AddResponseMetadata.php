<?php
declare(strict_types = 1);

namespace App\Http\Middleware;

/**
 * Class AddResponseMetadata
 * @package App\Http\Middleware
 */
class AddResponseMetadata
{

    /**
     * Add response metadata
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
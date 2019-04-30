<?php
declare(strict_types = 1);

namespace App\Http\Middleware;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

/**
 * Class PrettyPrint
 * @package App\Http\Middleware
 */
class PrettyPrint
{

    /**
     * Apply pretty print if designated
     *
     * @param $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {

        $response = $next($request);

        if (in_array(app('env'), ['local', 'testing']) === false) {
            return $response;
        }

        // https://www.aaronsaray.com/2017/laravel-pretty-print-middleware
        if ($response instanceof JsonResponse) {
            $response->setEncodingOptions(config('app.json_encode_options'));
        }
        if ($response instanceof Response &&
            $response->headers->get('Content-Type') === 'application/json') {
            $content = $response->getContent();
            $content = json_decode($content);
            $content = json_encode($content, config('app.json_encode_options'));
            $response->setContent($content);
        }

        return $response;
    }

}
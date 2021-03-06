<?php
declare(strict_types = 1);

namespace App\Http\Middleware;

use Illuminate\Http\Request;

/**
 * Class RemoveResponseEmptyItems
 * @package App\Http\Middleware
 */
class RemoveResponseEmptyItems
{

    /**
     * Remove empty items (with default array_filter behaviour, so far) from response.
     *
     * @param Request $request
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle(Request $request, \Closure $next)
    {

        $response = $next($request);

        if ($response->status() === 200 &&
            empty($response->getContent()) === false) {

            $data = json_decode($response->getContent(), true);
            $data = $this->array_filter_recursive(
                $data,
                // @todo improve this logic
                function ($value) {
                    return
                        $value !== null &&
                        $value !== [] &&
                        $value !== '';
                }
            );
            $response->setContent($data);

        }

        return $response;

    }

    /**
     * Recursively filter an array
     *
     * @param array $array
     * @param callable $callback
     *
     * @return array
     *
     * @see https://wpscholar.com/blog/filter-multidimensional-array-php/
     */
    protected function array_filter_recursive(array $array, callable $callback = null) : array
    {

        $array = is_callable($callback) ? array_filter($array, $callback) : array_filter($array);
        foreach ($array as &$value) {
            if (is_array($value)) {
                $value = call_user_func(__METHOD__, $value, $callback);
            }
        }

        return $array;

    }

}
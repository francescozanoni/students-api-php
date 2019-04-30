<?php
declare(strict_types = 1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     */
    public function register()
    {

        /* Example output of print_r($app->request->route()):
         *
         * Array (
         *   [0] => 1
         *   [1] => Array (
         *     [as] => getStudentById
         *     [uses] => App\Http\Controllers\StudentsController@show
         *     [middleware] => Array (
         *       [0] => validate_request
         *     )
         *   )
         *   [2] => Array (
         *     [id] => 1
         *   )
         * )
         */

        // This binding works only from route middlewares on (within request/response life cycle).
        // Before any route middlewares, null is returned.
        $this->app->bind('current_route_alias', function ($app) {
            $route = $app->request->route();
            return $route[1]['as'] ?? null;
        });

        // Get the current route path, e.g. /students/{id}
        $this->app->bind('current_route_path', function ($app) {

            $originalRoute = $app->request->route();

            if (isset($originalRoute[1]['as']) === false) {
                return null;
            }

            $foundRoutes = array_filter(
                $app['router']->getRoutes(),
                function ($otherRoute) use ($originalRoute) {
                    return
                        isset($otherRoute['action']) === true &&
                        isset($otherRoute['action']['as']) === true &&
                        $otherRoute['action']['as'] === $originalRoute[1]['as'];
                }
            );

            return empty($foundRoutes) === false ? reset($foundRoutes)['uri'] : null;

        });

        $this->app->bind('current_route_path_parameters', function ($app) {
            $route = $app->request->route();
            return $route[2] ?? [];
        });

    }

}

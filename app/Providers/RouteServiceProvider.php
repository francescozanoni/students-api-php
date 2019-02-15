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

            if (isset($route[1]) === true &&
                isset($route[1]['as']) === true) {
                return $route[1]['as'];
            }

            return null;

        });

        // Get the current route path, e.g. /students/{id}
        $this->app->bind('current_route_path', function ($app) {

            $route = $app->request->route();

            if (isset($route[1]) === false ||
                isset($route[1]['as']) === false) {
                return null;
            }

            $routeAlias = $route[1]['as'];
            $routes = $app['router']->getRoutes();
            $route = array_filter(
                $routes,
                function ($singleRoute) use ($routeAlias) {
                    return
                        isset($singleRoute['action']) === true &&
                        isset($singleRoute['action']['as']) === true &&
                        $singleRoute['action']['as'] === $routeAlias;
                }
            );

            if (empty($route) === false) {
                return reset($route)['uri'];
            }

            return null;

        });

        $this->app->bind('current_route_path_parameters', function ($app) {

            $route = $app->request->route();

            if (isset($route[2]) === true) {
                return $route[2];
            }

            return [];

        });

    }

}

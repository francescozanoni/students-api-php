<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;
use Tuupola\Http\Factory\ResponseFactory;
use Tuupola\Http\Factory\ServerRequestFactory;
use Tuupola\Http\Factory\StreamFactory;
use Tuupola\Http\Factory\UploadedFileFactory;

class AppServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

        // This binding works only from route middlewares on.
        // Before any route middlewares, null is returned.
        $this->app->bind('current_route_alias', function ($app) {

            /* Example output of print_r($app->request->route()):
             *
             * Array (
             *   [0] => 1
             *   [1] => Array (
             *     [as] => createStudent
             *     [uses] => App\Http\Controllers\StudentsController@store
             *     [middleware] => Array (
             *       [0] => validate_request
             *     )
             *   )
             *   [2] => Array ()
             * )
             */

            if (isset($app->request->route()[1]) === true &&
                isset($app->request->route()[1]['as']) === true) {
                return $app->request->route()[1]['as'];
            }

            return null;

        });

        // Get the current route path, e.g. /students/{id}
        $this->app->bind('current_route_path', function ($app) {

            if (isset($app->request->route()[1]) === true &&
                isset($app->request->route()[1]['as']) === true) {

                $routeAlias = $app->request->route()[1]['as'];
                $routes = app('router')->getRoutes();
                $route = array_filter(
                    $routes,
                    function ($r) use ($routeAlias) {
                        return isset($r['action']) === true &&
                            isset($r['action']['as']) === true &&
                            $r['action']['as'] === $routeAlias;
                    }
                );
                if (empty($route) === false) {
                    return reset($route)['uri'];
                }

            }


            return null;

        });

        $this->app->bind('resource_id_path_parameter', function ($app) {

            // $app->request->route('id') cannot be used, since during tests it triggers the following error:
            // "Call to a member function parameter() on array"

            if (isset($app->request->route()[2]) === true &&
                isset($app->request->route()[2]['id']) === true) {
                return $app->request->route()[2]['id'];
            }

            return null;

        });

        $this->app->bind('psr7_request', function ($app) {

            $factory = new PsrHttpFactory (
                new ServerRequestFactory(),
                new StreamFactory(),
                new UploadedFileFactory(),
                new ResponseFactory()
            );

            $psr7Request = $factory->createRequest($app->request);

            return $psr7Request;

        });

    }

}

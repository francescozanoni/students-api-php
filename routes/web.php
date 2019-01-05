<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
 */

$router->get('/', function () use ($router) {

    // If Swagger UI is available, automatic redirection to it.
    if (file_exists(config('openapi.schema_file_path')) === true) {
        return redirect(app('url')->to(config('openapi.swagger_ui_url') . '?url=' . app('url')->to(basename(config('openapi.schema_file_path')))));
    }

    return $router->app->version();

});

$router->get('students', 'StudentsController@list');


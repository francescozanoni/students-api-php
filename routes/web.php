<?php
declare(strict_types = 1);

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

    $openapiSchemaFilePath = config('openapi.schema_file_path');
    $openapiSwaggerUiUrl = app('url')->to(config('openapi.swagger_ui_url'));
    // If Swagger UI is available, automatic redirection to it.
    if (file_exists($openapiSchemaFilePath) === true) {
        return redirect($openapiSwaggerUiUrl . '?url=' . app('url')->to(basename($openapiSchemaFilePath)));
    }

    return $router->app->version();

});

$router->group(['middleware' => 'validate_request'], function () use ($router) {
    $router->get('students', ['as' => 'getStudents'], 'StudentsController@index');
$router->post('students',  ['as' => 'createStudent'], 'StudentsController@store');
$router->get('students/{id}', ['as' => 'getStudentById'], 'StudentsController@show');
$router->put('students/{id}', ['as' => 'updateStudentById'], 'StudentsController@update');
$router->delete('students/{id}', ['as' => 'deleteStudentById'], 'StudentsController@destroy');
});
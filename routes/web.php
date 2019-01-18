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
    $openapiSchemaUrl = app('url')->to(basename($openapiSchemaFilePath));
    $openapiSwaggerUiUrl = app('url')->to(config('openapi.swagger_ui_url'));
    
    // If Swagger UI is available, automatic redirection to it.
    if (file_exists($openapiSchemaFilePath) === true) {
        return redirect($openapiSwaggerUiUrl . '?url=' . $openapiSchemaUrl);
    }

    return $router->app->version();

});

$router->group(['middleware' => 'validate_request'], function () use ($router) {

    $router->get('students', ['as' => 'getStudents', 'uses' => 'StudentsController@index']);
    $router->post('students',  ['as' => 'createStudent', 'uses' => 'StudentsController@store']);
    $router->get('students/{id}', ['as' => 'getStudentById', 'uses' => 'StudentsController@show']);
    $router->put('students/{id}', ['as' => 'updateStudentById', 'uses' => 'StudentsController@update']);
    $router->delete('students/{id}', ['as' => 'deleteStudentById', 'uses' => 'StudentsController@destroy']);

    $router->get('students/{id}/annotations', ['as' => 'getStudentAnnotations', 'uses' => 'AnnotationsController@getRelatedToStudent']);

    $router->get('annotations', ['as' => 'getAnnotations', 'uses' => 'AnnotationsController@index']);

});
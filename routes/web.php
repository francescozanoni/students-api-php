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

$router->get('/', ['as' => 'root', function () use ($router) {

    $openapiSchemaFilePath = config('openapi.schema_file_path');
    $openapiSchemaUrl = app('url')->to(basename($openapiSchemaFilePath));
    $openapiSwaggerUiUrl = app('url')->to(config('openapi.swagger_ui_url'));

    // If Swagger UI is available, automatic redirection to it.
    if (file_exists($openapiSchemaFilePath) === true) {
        return redirect($openapiSwaggerUiUrl . '?url=' . $openapiSchemaUrl);
    }

    return $router->app->version();

}]);

$router->group(['middleware' => 'validate_request'], function () use ($router) {

    $router->get('students', ['as' => 'getStudents', 'uses' => 'StudentsController@index']);
    $router->post('students', ['as' => 'createStudent', 'uses' => 'StudentsController@store']);
    $router->get('students/{id}', ['as' => 'getStudentById', 'uses' => 'StudentsController@show']);
    $router->put('students/{id}', ['as' => 'updateStudentById', 'uses' => 'StudentsController@update']);
    $router->delete('students/{id}', ['as' => 'deleteStudentById', 'uses' => 'StudentsController@destroy']);

    $router->get('students/{id}/annotations', ['as' => 'getStudentAnnotations', 'uses' => 'AnnotationsController@getRelatedToStudent']);
    $router->post('students/{id}/annotations', ['as' => 'createStudentAnnotation', 'uses' => 'AnnotationsController@createRelatedToStudent']);

    $router->get('annotations', ['as' => 'getAnnotations', 'uses' => 'AnnotationsController@index']);
    $router->get('annotations/{id}', ['as' => 'getAnnotationById', 'uses' => 'AnnotationsController@show']);
    $router->put('annotations/{id}', ['as' => 'updateAnnotationById', 'uses' => 'AnnotationsController@update']);
    $router->delete('annotations/{id}', ['as' => 'deleteAnnotationById', 'uses' => 'AnnotationsController@destroy']);

    $router->get('students/{id}/stages', ['as' => 'getStudentStages', 'uses' => 'StagesController@getRelatedToStudent']);
    $router->post('students/{id}/stages', ['as' => 'createStudentStage', 'uses' => 'StagesController@createRelatedToStudent']);

    $router->get('stages', ['as' => 'getStages', 'uses' => 'StagesController@index']);
    $router->get('stages/{id}', ['as' => 'getStageById', 'uses' => 'StagesController@show']);
    $router->put('stages/{id}', ['as' => 'updateStageById', 'uses' => 'StagesController@update']);
    $router->delete('stages/{id}', ['as' => 'deleteStageById', 'uses' => 'StagesController@destroy']);

});
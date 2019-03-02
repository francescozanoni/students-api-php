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

    $router->get('students/{id}/seminar_attendances', ['as' => 'getStudentSeminarAttendances', 'uses' => 'SeminarAttendancesController@getRelatedToStudent']);
    $router->post('students/{id}/seminar_attendances', ['as' => 'createStudentSeminarAttendance', 'uses' => 'SeminarAttendancesController@createRelatedToStudent']);

    $router->get('seminar_attendances', ['as' => 'getSeminarAttendances', 'uses' => 'SeminarAttendancesController@index']);
    $router->get('seminar_attendances/{id}', ['as' => 'getSeminarAttendanceById', 'uses' => 'SeminarAttendancesController@show']);
    $router->put('seminar_attendances/{id}', ['as' => 'updateSeminarAttendanceById', 'uses' => 'SeminarAttendancesController@update']);
    $router->delete('seminar_attendances/{id}', ['as' => 'deleteSeminarAttendanceById', 'uses' => 'SeminarAttendancesController@destroy']);

    $router->get('students/{id}/educational_activity_attendances', ['as' => 'getStudentEducationalActivityAttendances', 'uses' => 'EducationalActivityAttendancesController@getRelatedToStudent']);
    $router->post('students/{id}/educational_activity_attendances', ['as' => 'createStudentEducationalActivityAttendance', 'uses' => 'EducationalActivityAttendancesController@createRelatedToStudent']);

    $router->get('educational_activity_attendances', ['as' => 'getEducationalActivityAttendances', 'uses' => 'EducationalActivityAttendancesController@index']);
    $router->get('educational_activity_attendances/{id}', ['as' => 'getEducationalActivityAttendanceById', 'uses' => 'EducationalActivityAttendancesController@show']);
    $router->put('educational_activity_attendances/{id}', ['as' => 'updateEducationalActivityAttendanceById', 'uses' => 'EducationalActivityAttendancesController@update']);
    $router->delete('educational_activity_attendances/{id}', ['as' => 'deleteEducationalActivityAttendanceById', 'uses' => 'EducationalActivityAttendancesController@destroy']);

    $router->get('stages/{id}/evaluation', ['as' => 'getStageEvaluation', 'uses' => 'EvaluationsController@getRelatedToStage']);
    $router->post('stages/{id}/evaluation', ['as' => 'createStageEvaluation', 'uses' => 'EvaluationsController@createRelatedToStage']);

    $router->get('evaluations', ['as' => 'getEvaluations', 'uses' => 'EvaluationsController@index']);
    $router->get('evaluations/{id}', ['as' => 'getEvaluationById', 'uses' => 'EvaluationsController@show']);
    $router->put('evaluations/{id}', ['as' => 'updateEvaluationById', 'uses' => 'EvaluationsController@update']);
    $router->delete('evaluations/{id}', ['as' => 'deleteEvaluationById', 'uses' => 'EvaluationsController@destroy']);

    $router->get('stages/{id}/interruption_report', ['as' => 'getStageInterruptionReport', 'uses' => 'InterruptionReportsController@getRelatedToStage']);
    $router->post('stages/{id}/interruption_report', ['as' => 'createStageInterruptionReport', 'uses' => 'InterruptionReportsController@createRelatedToStage']);

    $router->get('interruption_reports', ['as' => 'getInterruptionReports', 'uses' => 'InterruptionReportsController@index']);
    $router->get('interruption_reports/{id}', ['as' => 'getInterruptionReportById', 'uses' => 'InterruptionReportsController@show']);
    $router->put('interruption_reports/{id}', ['as' => 'updateInterruptionReportById', 'uses' => 'InterruptionReportsController@update']);
    $router->delete('interruption_reports/{id}', ['as' => 'deleteInterruptionReportById', 'uses' => 'InterruptionReportsController@destroy']);

});
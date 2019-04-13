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

    $router->get('students/{id}/internships', ['as' => 'getStudentInternships', 'uses' => 'InternshipsController@getRelatedToStudent']);
    $router->post('students/{id}/internships', ['as' => 'createStudentInternship', 'uses' => 'InternshipsController@createRelatedToStudent']);

    $router->get('internships', ['as' => 'getInternships', 'uses' => 'InternshipsController@index']);
    $router->get('internships/{id}', ['as' => 'getInternshipById', 'uses' => 'InternshipsController@show']);
    $router->put('internships/{id}', ['as' => 'updateInternshipById', 'uses' => 'InternshipsController@update']);
    $router->delete('internships/{id}', ['as' => 'deleteInternshipById', 'uses' => 'InternshipsController@destroy']);

    $router->get('students/{id}/educational_activity_attendances', ['as' => 'getStudentEducationalActivityAttendances', 'uses' => 'EducationalActivityAttendancesController@getRelatedToStudent']);
    $router->post('students/{id}/educational_activity_attendances', ['as' => 'createStudentEducationalActivityAttendance', 'uses' => 'EducationalActivityAttendancesController@createRelatedToStudent']);

    $router->get('educational_activity_attendances', ['as' => 'getEducationalActivityAttendances', 'uses' => 'EducationalActivityAttendancesController@index']);
    $router->get('educational_activity_attendances/{id}', ['as' => 'getEducationalActivityAttendanceById', 'uses' => 'EducationalActivityAttendancesController@show']);
    $router->put('educational_activity_attendances/{id}', ['as' => 'updateEducationalActivityAttendanceById', 'uses' => 'EducationalActivityAttendancesController@update']);
    $router->delete('educational_activity_attendances/{id}', ['as' => 'deleteEducationalActivityAttendanceById', 'uses' => 'EducationalActivityAttendancesController@destroy']);

    $router->get('internships/{id}/evaluation', ['as' => 'getInternshipEvaluation', 'uses' => 'EvaluationsController@getRelatedToInternship']);
    $router->post('internships/{id}/evaluation', ['as' => 'createInternshipEvaluation', 'uses' => 'EvaluationsController@createRelatedToInternship']);

    $router->get('evaluations', ['as' => 'getEvaluations', 'uses' => 'EvaluationsController@index']);
    $router->get('evaluations/{id}', ['as' => 'getEvaluationById', 'uses' => 'EvaluationsController@show']);
    $router->put('evaluations/{id}', ['as' => 'updateEvaluationById', 'uses' => 'EvaluationsController@update']);
    $router->delete('evaluations/{id}', ['as' => 'deleteEvaluationById', 'uses' => 'EvaluationsController@destroy']);

    $router->get('internships/{id}/interruption_report', ['as' => 'getInternshipInterruptionReport', 'uses' => 'InterruptionReportsController@getRelatedToInternship']);
    $router->post('internships/{id}/interruption_report', ['as' => 'createInternshipInterruptionReport', 'uses' => 'InterruptionReportsController@createRelatedToInternship']);

    $router->get('interruption_reports', ['as' => 'getInterruptionReports', 'uses' => 'InterruptionReportsController@index']);
    $router->get('interruption_reports/{id}', ['as' => 'getInterruptionReportById', 'uses' => 'InterruptionReportsController@show']);
    $router->put('interruption_reports/{id}', ['as' => 'updateInterruptionReportById', 'uses' => 'InterruptionReportsController@update']);
    $router->delete('interruption_reports/{id}', ['as' => 'deleteInterruptionReportById', 'uses' => 'InterruptionReportsController@destroy']);

    $router->get('students/{id}/eligibilities', ['as' => 'getStudentEligibilities', 'uses' => 'EligibilitiesController@getRelatedToStudent']);
    $router->post('students/{id}/eligibilities', ['as' => 'createStudentEligibility', 'uses' => 'EligibilitiesController@createRelatedToStudent']);

    $router->get('eligibilities', ['as' => 'getEligibilities', 'uses' => 'EligibilitiesController@index']);
    $router->get('eligibilities/{id}', ['as' => 'getEligibilityById', 'uses' => 'EligibilitiesController@show']);
    $router->put('eligibilities/{id}', ['as' => 'updateEligibilityById', 'uses' => 'EligibilitiesController@update']);
    $router->delete('eligibilities/{id}', ['as' => 'deleteEligibilityById', 'uses' => 'EligibilitiesController@destroy']);

    $router->get('students/{id}/osh_course_attendances', ['as' => 'getStudentOshCourseAttendances', 'uses' => 'OshCourseAttendancesController@getRelatedToStudent']);
    $router->post('students/{id}/osh_course_attendances', ['as' => 'createStudentOshCourseAttendance', 'uses' => 'OshCourseAttendancesController@createRelatedToStudent']);

    $router->get('osh_course_attendances', ['as' => 'getOshCourseAttendances', 'uses' => 'OshCourseAttendancesController@index']);
    $router->get('osh_course_attendances/{id}', ['as' => 'getOshCourseAttendanceById', 'uses' => 'OshCourseAttendancesController@show']);
    $router->put('osh_course_attendances/{id}', ['as' => 'updateOshCourseAttendanceById', 'uses' => 'OshCourseAttendancesController@update']);
    $router->delete('osh_course_attendances/{id}', ['as' => 'deleteOshCourseAttendanceById', 'uses' => 'OshCourseAttendancesController@destroy']);

});

// Endpint used only to test error handler.
if (env('APP_ENV') === 'testing') {
    $router->get('/test', ['as' => 'test', function () {
        throw new Exception('TEST EXCEPTION');
    }]);
}

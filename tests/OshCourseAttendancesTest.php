<?php
declare(strict_types = 1);

class OshCourseAttendancesTest extends TestCase
{

    /**
     * Get all Occupational Safety and Health course attendances.
     */
    public function testGet()
    {
        $this->json('GET', '/osh_course_attendances')
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource(s) found',
                'data' => [
                    [
                        'id' => 1,
                        'start_date' => '2018-12-08',
                        'end_date' => '2019-12-07',
                        'student' => [
                            'id' => 1,
                            'first_name' => 'John',
                            'last_name' => 'Doe',
                            'e_mail' => 'john.doe@foo.com',
                            'phone' => '1234-567890',
                            'nationality' => 'GB',
                        ],
                    ],
                    [
                        'id' => 3,
                        'start_date' => '2018-12-14',
                        'end_date' => '2019-12-13',
                        'student' => [
                            'id' => 4,
                            'first_name' => 'Joan',
                            'last_name' => 'Doe',
                            'e_mail' => 'joan.doe@foo.com',
                            'nationality' => 'IE',
                        ],
                    ],
                ]
            ])
            ->seeStatusCode(200);
    }

    /**
     * Get Occupational Safety and Health course attendance by ID.
     */
    public function testGetById()
    {

        // Existing
        $this->json('GET', '/osh_course_attendances/1')
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource successfully retrieved/created/modified',
                'data' => [
                    'id' => 1,
                    'start_date' => '2018-12-08',
                    'end_date' => '2019-12-07',
                    'student' => [
                        'id' => 1,
                        'first_name' => 'John',
                        'last_name' => 'Doe',
                        'e_mail' => 'john.doe@foo.com',
                        'phone' => '1234-567890',
                        'nationality' => 'GB',
                    ],
                ],
            ])
            ->seeStatusCode(200);

    }

    /**
     * Get Occupational Safety and Health course attendance by ID: failure.
     */
    public function testGetByIdFailure()
    {

        // Non existing
        $this->json('GET', '/osh_course_attendances/9999')
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ])
            ->seeStatusCode(404);

        // Deleted
        $this->json('GET', '/osh_course_attendances/2')
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ])
            ->seeStatusCode(404);

        // Invalid ID
        $this->json('GET', '/osh_course_attendances/abc')
            ->seeJsonEquals([
                'status_code' => 400,
                'status' => 'Bad Request',
                'message' => 'Request is not valid',
                'data' => [
                    'id' => [
                        'code error_type',
                        'value abc',
                        'expected integer',
                        'used string',
                        'in path',
                    ],
                ]
            ])
            ->seeStatusCode(400);

    }

    /**
     * Get student's Occupational Safety and Health course attendances: success.
     */
    public function testGetRelatedToStudent()
    {

        // Existing
        $this->json('GET', '/students/1/osh_course_attendances')
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource(s) found',
                'data' => [
                    [
                        'id' => 1,
                        'start_date' => '2018-12-08',
                        'end_date' => '2019-12-07',
                    ]
                ],
            ])
            ->seeStatusCode(200);

    }

    /**
     * Get student's Occupational Safety and Health course attendances: failure.
     */
    public function testGetRelatedToStudentFailure()
    {

        // Non existing Occupational Safety and Health course attendances
        $this->json('GET', '/students/2/osh_course_attendances')
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ])
            ->seeStatusCode(404);

        // Non existing student
        $this->json('GET', '/students/999/osh_course_attendances')
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ])
            ->seeStatusCode(404);

        // Invalid student ID
        $this->json('GET', '/students/abc/osh_course_attendances')
            ->seeJsonEquals([
                'status_code' => 400,
                'status' => 'Bad Request',
                'message' => 'Request is not valid',
                'data' => [
                    'id' => [
                        'code error_type',
                        'value abc',
                        'expected integer',
                        'used string',
                        'in path',
                    ],
                ]
            ])
            ->seeStatusCode(400);

    }
    
}

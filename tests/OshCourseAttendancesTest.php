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

    /**
     * Create a student's Occupational Safety and Health course attendance.
     */
    public function testCreateRelatedToStudent()
    {

        // Existing student
        $this->json('POST',
            '/students/1/osh_course_attendances',
            [
                'start_date' => '2019-12-08',
                'end_date' => '2020-12-07',
            ]
        )
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource successfully retrieved/created/modified',
                'data' => [
                    'id' => 4,
                    'start_date' => '2019-12-08',
                    'end_date' => '2020-12-07',
                ],
            ])
            ->seeStatusCode(200)
            ->seeInDatabase('osh_course_attendances', ['id' => 4, 'student_id' => 1, 'deleted_at' => null])
            ->notSeeInDatabase('osh_course_attendances', ['id' => 5]);

    }

    /**
     * Create a student's Occupational Safety and Health course attendance: failure.
     */
    public function testCreateRelatedToStudentFailure()
    {

        // Non existing student
        $this->json('POST',
            '/students/999/osh_course_attendances',
            [
                'start_date' => '2019-12-08',
                'end_date' => '2020-12-07',
            ]
        )
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ])
            ->seeStatusCode(404)
            ->notSeeInDatabase('osh_course_attendances', ['id' => 4])
            ->notSeeInDatabase('osh_course_attendances', ['student_id' => 999]);

        // Deleted student
        $this->json('POST',
            '/students/3/osh_course_attendances',
            [
                'start_date' => '2019-12-08',
                'end_date' => '2020-12-07',
            ]
        )
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ])
            ->seeStatusCode(404)
            ->notSeeInDatabase('osh_course_attendances', ['id' => 4])
            ->notSeeInDatabase('osh_course_attendances', ['student_id' => 999]);

        // Invalid student ID
        $this->json('POST',
            '/students/abc/osh_course_attendances',
            [
                'start_date' => '2019-12-08',
                'end_date' => '2020-12-07',
            ]
        )
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
            ->seeStatusCode(400)
            ->notSeeInDatabase('osh_course_attendances', ['id' => 4])
            ->notSeeInDatabase('osh_course_attendances', ['student_id' => 'abc']);

        // Missing required start_date
        $this->json('POST',
            '/students/1/osh_course_attendances',
            [
                'end_date' => '2020-12-07',
            ]
        )
            ->seeJsonEquals([
                'status_code' => 400,
                'status' => 'Bad Request',
                'message' => 'Request is not valid',
                'data' => [
                    'start_date' => [
                        'code error_required',
                        'in body',
                    ]
                ]
            ])
            ->seeStatusCode(400)
            ->notSeeInDatabase('osh_course_attendances', ['id' => 4]);

        // Missing required end_date
        $this->json('POST',
            '/students/1/osh_course_attendances',
            [
                'start_date' => '2019-12-08',
            ]
        )
            ->seeJsonEquals([
                'status_code' => 400,
                'status' => 'Bad Request',
                'message' => 'Request is not valid',
                'data' => [
                    'end_date' => [
                        'code error_required',
                        'in body',
                    ]
                ]
            ])
            ->seeStatusCode(400)
            ->notSeeInDatabase('osh_course_attendances', ['id' => 4]);

        // Unallowed additional property.
        $this->json('POST',
            '/students/1/osh_course_attendances',
            [
                'start_date' => '2019-12-08',
                'end_date' => '2020-12-07',
                'an_additional_property' => 'an additional value',
            ]
        )
            ->seeJsonEquals([
                'status_code' => 400,
                'status' => 'Bad Request',
                'message' => 'Request is not valid',
                'data' => [
                    'an_additional_property' => [
                        'code error_additional',
                        'value an additional value',
                        'in body',
                    ]
                ]
            ])
            ->seeStatusCode(400)
            ->notSeeInDatabase('osh_course_attendances', ['id' => 4]);

        // Overlapping dates - 1
        $this->json('POST',
            '/students/1/osh_course_attendances',
            [
                'start_date' => '2018-12-08',
                'end_date' => '2019-12-07',
            ]
        )
            ->seeJsonEquals([
                'status_code' => 400,
                'status' => 'Bad Request',
                'message' => 'Request is not valid',
                'data' => [
                    'start_date' => [
                        'Unavailable time range',
                    ],
                    'end_date' => [
                        'Unavailable time range',
                    ],
                ]
            ])
            ->seeStatusCode(400)
            ->notSeeInDatabase('osh_course_attendances', ['id' => 4]);

        // Overlapping dates - 2
        $this->json('POST',
            '/students/1/osh_course_attendances',
            [
                'start_date' => '2018-10-08',
                'end_date' => '2019-10-07',
            ]
        )
            ->seeJsonEquals([
                'status_code' => 400,
                'status' => 'Bad Request',
                'message' => 'Request is not valid',
                'data' => [
                    'start_date' => [
                        'Unavailable time range',
                    ],
                    'end_date' => [
                        'Unavailable time range',
                    ],
                ]
            ])
            ->seeStatusCode(400)
            ->notSeeInDatabase('osh_course_attendances', ['id' => 4]);

        // Overlapping dates - 3
        $this->json('POST',
            '/students/1/osh_course_attendances',
            [
                'start_date' => '2019-02-08',
                'end_date' => '2020-02-07',
            ]
        )
            ->seeJsonEquals([
                'status_code' => 400,
                'status' => 'Bad Request',
                'message' => 'Request is not valid',
                'data' => [
                    'start_date' => [
                        'Unavailable time range',
                    ],
                    'end_date' => [
                        'Unavailable time range',
                    ],
                ]
            ])
            ->seeStatusCode(400)
            ->notSeeInDatabase('osh_course_attendances', ['id' => 4]);

        // Identical dates
        $this->json('POST',
            '/students/1/osh_course_attendances',
            [
                'start_date' => '2019-12-08',
                'end_date' => '2019-12-08',
            ]
        )
            ->seeJsonEquals([
                'status_code' => 400,
                'status' => 'Bad Request',
                'message' => 'Request is not valid',
                'data' => [
                    'start_date' => [
                        'The start date must be a date before end date',
                    ],
                    'end_date' => [
                        'The end date must be a date after start date',
                    ],
                ]
            ])
            ->seeStatusCode(400)
            ->notSeeInDatabase('osh_course_attendances', ['id' => 4]);

        // Switched dates
        $this->json('POST',
            '/students/1/osh_course_attendances',
            [
                'start_date' => '2020-12-07',
                'end_date' => '2019-12-08',
            ]
        )
            ->seeJsonEquals([
                'status_code' => 400,
                'status' => 'Bad Request',
                'message' => 'Request is not valid',
                'data' => [
                    'start_date' => [
                        'The start date must be a date before end date',
                    ],
                    'end_date' => [
                        'The end date must be a date after start date',
                    ],
                ]
            ])
            ->seeStatusCode(400)
            ->notSeeInDatabase('osh_course_attendances', ['id' => 4]);

        // @todo add further tests related to invalid attribute format

    }
    
}

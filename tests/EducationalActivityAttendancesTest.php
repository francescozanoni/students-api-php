<?php
declare(strict_types = 1);

class EducationalActivityAttendancesTest extends TestCase
{

    /**
     * Get all educational activity attendances.
     */
    public function testGet()
    {

        $this->json('GET', '/educational_activity_attendances')
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource(s) found',
                'data' => [
                    [
                        'id' => 1,
                        'educational_activity' => 'First educational activity',
                        'start_date' => '2019-01-08',
                        'end_date' => '2019-01-09',
                        'credits' => 1.2,
                        'student' => [
                            'id' => 1,
                            'first_name' => 'John',
                            'last_name' => 'Doe',
                            'e_mail' => 'john.doe@foo.com',
                            'phone' => '1234-567890',
                            'nationality' => 'GB',
                        ],
                    ],
                ],
            ])
            ->seeStatusCode(200);

    }

    /**
     * Get educational activity attendance by ID.
     */
    public function testGetById()
    {

        // Existing
        $this->json('GET', '/educational_activity_attendances/1')
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource successfully retrieved/created/modified',
                'data' => [
                    'id' => 1,
                    'educational_activity' => 'First educational activity',
                    'start_date' => '2019-01-08',
                    'end_date' => '2019-01-09',
                    'credits' => 1.2,
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
     * Get educational activity attendance by ID: failure.
     */
    public function testGetByIdFailure()
    {

        // Non existing
        $this->json('GET', '/educational_activity_attendances/9999')
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ])
            ->seeStatusCode(404);

        // Invalid ID
        $this->json('GET', '/educational_activity_attendances/abc')
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
     * Get student's educational activity attendances: success.
     */
    public function testGetRelatedToStudent()
    {

        // Existing
        $this->json('GET', '/students/1/educational_activity_attendances')
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource(s) found',
                'data' => [
                    [
                        'id' => 1,
                        'educational_activity' => 'First educational activity',
                        'start_date' => '2019-01-08',
                        'end_date' => '2019-01-09',
                        'credits' => 1.2,
                    ]
                ],
            ])
            ->seeStatusCode(200);

    }

    /**
     * Get student's educational activity attendances: failure.
     */
    public function testGetRelatedToStudentFailure()
    {

        // Non existing educational activity attendances
        $this->json('GET', '/students/2/educational_activity_attendances')
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ])
            ->seeStatusCode(404);

        // Non existing student
        $this->json('GET', '/students/999/educational_activity_attendances')
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ])
            ->seeStatusCode(404);

        // Invalid student ID
        $this->json('GET', '/students/abc/educational_activity_attendances')
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
     * Create a student's educational activity attendance: success.
     */
    public function testCreateRelatedToStudent()
    {

        // Existing student, full data
        $this->json('POST',
            '/students/1/educational_activity_attendances',
            [
                'educational_activity' => 'Another educational activity',
                'start_date' => '2019-01-30',
                'end_date' => '2019-01-31',
                'credits' => 0.4,
            ]
        )
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource successfully retrieved/created/modified',
                'data' => [
                    'id' => 3,
                    'educational_activity' => 'Another educational activity',
                    'start_date' => '2019-01-30',
                    'end_date' => '2019-01-31',
                    'credits' => 0.4,
                ],
            ])
            ->seeStatusCode(200)
            ->seeInDatabase('educational_activity_attendances', ['id' => 3, 'student_id' => 1])
            ->notSeeInDatabase('educational_activity_attendances', ['id' => 4]);


        // Existing student, no end date
        $this->json('POST',
            '/students/2/educational_activity_attendances',
            [
                'educational_activity' => 'Another educational activity',
                'start_date' => '2019-01-30',
                'credits' => 0.4,
            ]
        )
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource successfully retrieved/created/modified',
                'data' => [
                    'id' => 4,
                    'educational_activity' => 'Another educational activity',
                    'start_date' => '2019-01-30',
                    'credits' => 0.4,
                ],
            ])
            ->seeStatusCode(200)
            ->seeInDatabase('educational_activity_attendances', ['id' => 4, 'student_id' => 2])
            ->notSeeInDatabase('educational_activity_attendances', ['id' => 5]);

    }

    /**
     * Create a student's educational activity attendance: failure.
     */
    public function testCreateRelatedToStudentFailure()
    {

        // Non existing student
        $this->json('POST',
            '/students/999/educational_activity_attendances',
            [
                'educational_activity' => 'Another educational activity',
                'start_date' => '2019-01-30',
                'end_date' => '2019-01-31',
                'credits' => 0.4,
            ]
        )
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ])
            ->seeStatusCode(404)
            ->notSeeInDatabase('educational_activity_attendances', ['id' => 3])
            ->notSeeInDatabase('educational_activity_attendances', ['student_id' => 999]);

        // Invalid student ID
        $this->json('POST',
            '/students/abc/educational_activity_attendances',
            [
                'educational_activity' => 'Another educational activity',
                'start_date' => '2019-01-30',
                'end_date' => '2019-01-31',
                'credits' => 0.4,
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
            ->notSeeInDatabase('educational_activity_attendances', ['id' => 3])
            ->notSeeInDatabase('educational_activity_attendances', ['student_id' => 'abc']);

        // Switched dates
        $this->json('POST',
            '/students/1/educational_activity_attendances',
            [
                'educational_activity' => 'Another educational activity',
                'start_date' => '2019-01-31',
                'end_date' => '2019-01-30',
                'credits' => 0.4,
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
            ->notSeeInDatabase('educational_activity_attendances', ['id' => 3])
            ->notSeeInDatabase('educational_activity_attendances', ['start_date' => '2019-01-31', 'end_date' => '2019-01-30']);

        // Identical dates
        $this->json('POST',
            '/students/1/educational_activity_attendances',
            [
                'educational_activity' => 'Another educational activity',
                'start_date' => '2019-01-30',
                'end_date' => '2019-01-30',
                'credits' => 0.4,
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
            ->notSeeInDatabase('educational_activity_attendances', ['id' => 3])
            ->notSeeInDatabase('educational_activity_attendances', ['start_date' => '2019-01-30', 'end_date' => '2019-01-30']);

        // Non unique student/educational activity/start date
        $this->json('POST',
            '/students/1/educational_activity_attendances',
            [
                'educational_activity' => 'First educational activity',
                'start_date' => '2019-01-08',
                'end_date' => '2019-01-10',
                'credits' => 0.4,
            ]
        )
            ->seeJsonEquals([
                'status_code' => 400,
                'status' => 'Bad Request',
                'message' => 'Request is not valid',
                'data' => [
                    'educational_activity' => [
                        'Combination of student, educational activity and start date already used',
                    ],
                    'start_date' => [
                        'Combination of student, educational activity and start date already used',
                    ],
                ]
            ])
            ->seeStatusCode(400)
            ->notSeeInDatabase('educational_activity_attendances', ['id' => 3])
            ->notSeeInDatabase('educational_activity_attendances', ['educational_activity' => 'First educational activity', 'start_date' => '2019-01-08', 'end_date' => '2019-01-10']);

        // Unallowed additional property.
        $this->json('POST',
            '/students/1/educational_activity_attendances',
            [
                'educational_activity' => 'Another educational activity',
                'start_date' => '2019-01-30',
                'end_date' => '2019-01-31',
                'credits' => 0.4,
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
            ->notSeeInDatabase('educational_activity_attendances', ['id' => 3]);

        // Missing required educational_activity
        $this->json('POST',
            '/students/1/educational_activity_attendances',
            [
                'start_date' => '2019-01-30',
                'credits' => 0.4,
            ]
        )
            ->seeJsonEquals([
                'status_code' => 400,
                'status' => 'Bad Request',
                'message' => 'Request is not valid',
                'data' => [
                    'educational_activity' => [
                        'code error_required',
                        'in body',
                    ]
                ]
            ])
            ->seeStatusCode(400)
            ->notSeeInDatabase('educational_activity_attendances', ['id' => 3]);

        // Missing required start_date
        $this->json('POST',
            '/students/1/educational_activity_attendances',
            [
                'educational_activity' => 'Another educational activity',
                'credits' => 0.4,
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
            ->notSeeInDatabase('educational_activity_attendances', ['id' => 3]);

        // Missing required credits
        $this->json('POST',
            '/students/1/educational_activity_attendances',
            [
                'educational_activity' => 'Another educational activity',
                'start_date' => '2019-01-30',
            ]
        )
            ->seeJsonEquals([
                'status_code' => 400,
                'status' => 'Bad Request',
                'message' => 'Request is not valid',
                'data' => [
                    'credits' => [
                        'code error_required',
                        'in body',
                    ]
                ]
            ])
            ->seeStatusCode(400)
            ->notSeeInDatabase('educational_activity_attendances', ['id' => 3]);

        // @todo add further tests related to invalid attribute format

    }

    /**
     * Modify an educational activity attendance: success.
     */
    public function testModifyById()
    {

        $this->json('PUT',
            '/educational_activity_attendances/1',
            [
                'educational_activity' => 'First educational activity',
                'start_date' => '2019-01-09', // --> modified
                'end_date' => '2019-01-10',   // --> modified
                'credits' => 1.0,        // --> modified
            ]
        )
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource successfully retrieved/created/modified',
                'data' => [
                    'id' => 1,
                    'educational_activity' => 'First educational activity',
                    'start_date' => '2019-01-09',
                    'end_date' => '2019-01-10',
                    'credits' => 1.0,
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
            ->seeStatusCode(200)
            ->seeInDatabase('educational_activity_attendances', ['id' => 1, 'start_date' => '2019-01-09', 'end_date' => '2019-01-10', 'credits' => 1.0])
            ->notSeeInDatabase('educational_activity_attendances', ['id' => 1, 'start_date' => '2019-01-08'])
            ->notSeeInDatabase('educational_activity_attendances', ['id' => 1, 'end_date' => '2019-01-09'])
            ->notSeeInDatabase('educational_activity_attendances', ['id' => 1, 'credits' => 1.2])
            ->notSeeInDatabase('educational_activity_attendances', ['id' => 3]);

    }

    /**
     * Modify an educational activity attendance: failure.
     */
    public function testModifyByIdFailure()
    {

        // Non existing ID
        $this->json('PUT',
            '/educational_activity_attendances/999',
            [
                'educational_activity' => 'First educational activity',
                'start_date' => '2019-01-09',
                'end_date' => '2019-01-10',
                'credits' => 1.0,
            ]
        )
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ])
            ->seeStatusCode(404)
            ->notSeeInDatabase('educational_activity_attendances', ['id' => 999])
            ->notSeeInDatabase('educational_activity_attendances', ['id' => 3]);

        // Invalid ID
        $this->json('PUT',
            '/educational_activity_attendances/abc',
            [
                'educational_activity' => 'First educational activity',
                'start_date' => '2019-01-09',
                'end_date' => '2019-01-10',
                'credits' => 1.0,
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
            ->notSeeInDatabase('educational_activity_attendances', ['id' => 999])
            ->notSeeInDatabase('educational_activity_attendances', ['id' => 3]);

        // The record created by this method is used by below tests.
        $this->testCreateRelatedToStudent();

        // Non unique student/educational activity/start date
        $this->json('PUT',
            '/educational_activity_attendances/3',
            [
                'educational_activity' => 'First educational activity', // --> same as record #1
                'start_date' => '2019-01-08', // --> same as record #1
                'end_date' => '2019-01-10',
                'credits' => 1.4,
            ]
        )
            ->seeJsonEquals([
                'status_code' => 400,
                'status' => 'Bad Request',
                'message' => 'Request is not valid',
                'data' => [
                    'educational_activity' => [
                        'Combination of student, educational activity and start date already used',
                    ],
                    'start_date' => [
                        'Combination of student, educational activity and start date already used',
                    ],
                ]
            ])
            ->seeStatusCode(400)
            ->notSeeInDatabase('educational_activity_attendances', ['id' => 3, 'educational_activity' => 'First educational activity', 'start_date' => '2019-01-08', 'end_date' => '2019-01-10']);

        // Unallowed additional property.
        $this->json('PUT',
            '/educational_activity_attendances/1',
            [
                'educational_activity' => 'First educational activity 1', // --> modified
                'start_date' => '2019-01-08',
                'end_date' => '2019-01-09',
                'credits' => 1.2,
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
            ->seeInDatabase('educational_activity_attendances', ['id' => 1])
            ->notSeeInDatabase('educational_activity_attendances', ['id' => 1, 'educational_activity' => 'First educational activity 1']);

        // @todo add further tests related to missing required fields
        // @todo add further tests related to invalid attribute format

    }

    /**
     * Delete an educational activity attendance: success.
     */
    public function testDeleteById()
    {

        // Existing educational activity
        $this->json('DELETE', '/educational_activity_attendances/1')
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource deleted',
            ])
            ->seeStatusCode(200)
            ->notSeeInDatabase('educational_activity_attendances', ['id' => 1]);

    }

    /**
     * Delete an educational activity attendance: failure.
     */
    public function testDeleteByIdFailure()
    {

        // Non existing educational activity
        $this->json('DELETE', '/educational_activity_attendances/999')
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ])
            ->seeStatusCode(404)
            ->notSeeInDatabase('educational_activity_attendances', ['id' => 999]);

        // Invalid ID
        $this->json('DELETE', '/educational_activity_attendances/abc')
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
            ->notSeeInDatabase('educational_activity_attendances', ['id' => 'abc']);

    }

}

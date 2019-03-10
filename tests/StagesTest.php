<?php
declare(strict_types = 1);

class StagesTest extends TestCase
{

    /**
     * Get all stages.
     *
     * @throws Exception
     */
    public function testGet()
    {

        $this->json('GET', '/stages')
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource(s) found',
                'data' => [
                    [
                        'id' => 1,
                        'location' => 'Location 1',
                        'sub_location' => 'Sub-location 1',
                        'student' => [
                            'id' => 1,
                            'first_name' => 'John',
                            'last_name' => 'Doe',
                            'e_mail' => 'john.doe@foo.com',
                            'phone' => '1234-567890',
                            'nationality' => 'GB',
                        ],
                        'start_date' => '2019-01-10',
                        'end_date' => '2019-01-24',
                        'hour_amount' => 123,
                        'other_amount' => 5,
                        'is_optional' => false,
                        'is_interrupted' => false
                    ],
                    [
                        'id' => 2,
                        'location' => 'Location 1',
                        'sub_location' => 'Sub-location 1',
                        'student' => [
                            'id' => 1,
                            'first_name' => 'John',
                            'last_name' => 'Doe',
                            'e_mail' => 'john.doe@foo.com',
                            'phone' => '1234-567890',
                            'nationality' => 'GB',
                        ],
                        'start_date' => '2019-01-26',
                        'end_date' => '2019-01-31',
                        'hour_amount' => 34,
                        'other_amount' => 0,
                        'is_optional' => true,
                        'is_interrupted' => true
                    ],
                    [
                        'id' => 3,
                        'location' => 'Location 1',
                        'sub_location' => 'Sub-location 1',
                        'student' => [
                            'id' => 4,
                            'first_name' => 'Joan',
                            'last_name' => 'Doe',
                            'e_mail' => 'joan.doe@foo.com',
                            'nationality' => 'IE',
                        ],
                        'start_date' => '2019-01-26',
                        'end_date' => '2019-01-31',
                        'hour_amount' => 34,
                        'other_amount' => 0,
                        'is_optional' => false,
                        'is_interrupted' => true
                    ],
                    [
                        'id' => 4,
                        'location' => 'Location 1',
                        'sub_location' => 'Sub-location 1',
                        'student' => [
                            'id' => 4,
                            'first_name' => 'Joan',
                            'last_name' => 'Doe',
                            'e_mail' => 'joan.doe@foo.com',
                            'nationality' => 'IE',
                        ],
                        // 10 days in the future.
                        'start_date' => (new DateTime())->add(new DateInterval('P10D'))->format('Y-m-d'),
                        // 20 days in the future.
                        'end_date' => (new DateTime())->add(new DateInterval('P20D'))->format('Y-m-d'),
                        'hour_amount' => 0,
                        'other_amount' => 0,
                        'is_optional' => false,
                        'is_interrupted' => true
                    ],
                ]
            ])
            ->seeStatusCode(200);

    }

    /**
     * Get stage by ID.
     */
    public function testGetById()
    {

        // Existing
        $this->json('GET', '/stages/1')
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource successfully retrieved/created/modified',
                'data' => [
                    'id' => 1,
                    'location' => 'Location 1',
                    'sub_location' => 'Sub-location 1',
                    'student' => [
                        'id' => 1,
                        'first_name' => 'John',
                        'last_name' => 'Doe',
                        'e_mail' => 'john.doe@foo.com',
                        'phone' => '1234-567890',
                        'nationality' => 'GB',
                    ],
                    'start_date' => '2019-01-10',
                    'end_date' => '2019-01-24',
                    'hour_amount' => 123,
                    'other_amount' => 5,
                    'is_optional' => false,
                    'is_interrupted' => false
                ],
            ])
            ->seeStatusCode(200);

    }

    /**
     * Get stage by ID: failure.
     */
    public function testGetByIdFailure()
    {

        // Non existing
        $this->json('GET', '/stages/9999')
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ])
            ->seeStatusCode(404);

        // Invalid ID
        $this->json('GET', '/stages/abc')
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
     * Get student's stages: success.
     */
    public function testGetRelatedToStudent()
    {

        // Existing
        $this->json('GET', '/students/1/stages')
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource(s) found',
                'data' => [
                    [
                        'id' => 1,
                        'location' => 'Location 1',
                        'sub_location' => 'Sub-location 1',
                        'start_date' => '2019-01-10',
                        'end_date' => '2019-01-24',
                        'hour_amount' => 123,
                        'other_amount' => 5,
                        'is_optional' => false,
                        'is_interrupted' => false
                    ],
                    [
                        'id' => 2,
                        'location' => 'Location 1',
                        'sub_location' => 'Sub-location 1',
                        'start_date' => '2019-01-26',
                        'end_date' => '2019-01-31',
                        'hour_amount' => 34,
                        'other_amount' => 0,
                        'is_optional' => true,
                        'is_interrupted' => true
                    ]
                ],
            ])
            ->seeStatusCode(200);

    }

    /**
     * Get student's stages: failure.
     */
    public function testGetRelatedToStudentFailure()
    {

        // Non existing stages
        $this->json('GET', '/students/2/stages')
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ])
            ->seeStatusCode(404);

        // Non existing student
        $this->json('GET', '/students/999/stages')
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ])
            ->seeStatusCode(404);

        // Invalid student ID
        $this->json('GET', '/students/abc/stages')
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
     * Create a student's stage: success.
     */
    public function testCreateRelatedToStudent()
    {

        // Existing student, with sub-location
        $this->json('POST',
            '/students/1/stages',
            [
                'location' => 'Location 1',
                'sub_location' => 'Sub-location 1',
                'start_date' => '2019-02-01',
                'end_date' => '2019-02-11',
                'hour_amount' => 0,
                'other_amount' => 0,
                'is_optional' => true,
                'is_interrupted' => false
            ]
        )
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource successfully retrieved/created/modified',
                'data' => [
                    'id' => 5,
                    'location' => 'Location 1',
                    'sub_location' => 'Sub-location 1',
                    'start_date' => '2019-02-01',
                    'end_date' => '2019-02-11',
                    'hour_amount' => 0,
                    'other_amount' => 0,
                    'is_optional' => true,
                    'is_interrupted' => false
                ],
            ])
            ->seeStatusCode(200)
            ->seeInDatabase('stages', ['id' => 5, 'student_id' => 1, 'location_id' => 1, 'sub_location_id' => 1, 'deleted_at' => null])
            ->notSeeInDatabase('stages', ['id' => 6]);
            
        // Existing student, without sub-location
        $this->json('POST',
            '/students/1/stages',
            [
                'location' => 'Location 1',
                'start_date' => '2019-04-01',
                'end_date' => '2019-04-11',
                'hour_amount' => 0,
                'other_amount' => 0,
                'is_optional' => true,
                'is_interrupted' => false
            ]
        )
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource successfully retrieved/created/modified',
                'data' => [
                    'id' => 6,
                    'location' => 'Location 1',
                    'start_date' => '2019-04-01',
                    'end_date' => '2019-04-11',
                    'hour_amount' => 0,
                    'other_amount' => 0,
                    'is_optional' => true,
                    'is_interrupted' => false
                ],
            ])
            ->seeStatusCode(200)
            ->seeInDatabase('stages', ['id' => 6, 'student_id' => 1, 'location_id' => null, 'sub_location_id' => 1, 'deleted_at' => null])
            ->notSeeInDatabase('stages', ['id' => 7]);

    }

    /**
     * Create a student's stage: failure.
     */
    public function testCreateRelatedToStudentFailure()
    {

        // Non existing student
        $this->json('POST',
            '/students/999/stages',
            [
                'location' => 'Location 1',
                'sub_location' => 'Sub-location 1',
                'start_date' => '2019-01-25',
                'end_date' => '2019-01-31',
                'hour_amount' => 0,
                'other_amount' => 0,
                'is_optional' => true,
                'is_interrupted' => false
            ]
        )
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ])
            ->seeStatusCode(404)
            ->notSeeInDatabase('stages', ['id' => 5])
            ->notSeeInDatabase('stages', ['student_id' => 999]);

        // Invalid student ID
        $this->json('POST',
            '/students/abc/stages',
            [
                'location' => 'Location 1',
                'sub_location' => 'Sub-location 1',
                'start_date' => '2019-01-25',
                'end_date' => '2019-01-31',
                'hour_amount' => 0,
                'other_amount' => 0,
                'is_optional' => true,
                'is_interrupted' => false
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
            ->notSeeInDatabase('stages', ['id' => 5])
            ->notSeeInDatabase('stages', ['student_id' => 'abc']);

        // Non existing location
        $this->json('POST',
            '/students/1/stages',
            [
                'location' => 'Location 999',
                'sub_location' => 'Sub-location 1',
                'start_date' => '2019-02-25',
                'end_date' => '2019-02-28',
                'hour_amount' => 0,
                'other_amount' => 0,
                'is_optional' => true,
                'is_interrupted' => false
            ]
        )
            ->seeJsonEquals([
                'status_code' => 400,
                'status' => 'Bad Request',
                'message' => 'Request is not valid',
                'data' => [
                    'location' => [
                        'The location must be a valid location',
                    ],
                ]
            ])
            ->seeStatusCode(400)
            ->notSeeInDatabase('stages', ['id' => 5]);

        // Non existing sub-location
        $this->json('POST',
            '/students/1/stages',
            [
                'location' => 'Location 1',
                'sub_location' => 'Sub-location 999',
                'start_date' => '2019-02-25',
                'end_date' => '2019-02-28',
                'hour_amount' => 0,
                'other_amount' => 0,
                'is_optional' => true,
                'is_interrupted' => false
            ]
        )
            ->seeJsonEquals([
                'status_code' => 400,
                'status' => 'Bad Request',
                'message' => 'Request is not valid',
                'data' => [
                    'sub_location' => [
                        'The sub location must be a valid sub-location',
                    ],
                ]
            ])
            ->seeStatusCode(400)
            ->notSeeInDatabase('stages', ['id' => 5]);

        // Switched dates
        $this->json('POST',
            '/students/1/stages',
            [
                'location' => 'Location 1',
                'sub_location' => 'Sub-location 1',
                'start_date' => '2019-02-28',
                'end_date' => '2019-02-25',
                'hour_amount' => 0,
                'other_amount' => 0,
                'is_optional' => true,
                'is_interrupted' => false
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
            ->notSeeInDatabase('stages', ['id' => 5])
            ->notSeeInDatabase('stages', ['start_date' => '2019-01-31', 'end_date' => '2019-01-25']);

        // Identical dates
        $this->json('POST',
            '/students/1/stages',
            [
                'location' => 'Location 1',
                'sub_location' => 'Sub-location 1',
                'start_date' => '2019-02-25',
                'end_date' => '2019-02-25',
                'hour_amount' => 0,
                'other_amount' => 0,
                'is_optional' => true,
                'is_interrupted' => false
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
            ->notSeeInDatabase('stages', ['id' => 5])
            ->notSeeInDatabase('stages', ['start_date' => '2019-01-25', 'end_date' => '2019-01-25']);

        // Overlapping time range
        $this->json('POST',
            '/students/1/stages',
            [
                'location' => 'Location 1',
                'sub_location' => 'Sub-location 1',
                'start_date' => '2019-01-05',
                'end_date' => '2019-01-20',
                'hour_amount' => 0,
                'other_amount' => 0,
                'is_optional' => true,
                'is_interrupted' => false
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
            ->notSeeInDatabase('stages', ['id' => 5]);
        $this->json('POST',
            '/students/1/stages',
            [
                'location' => 'Location 1',
                'sub_location' => 'Sub-location 1',
                'start_date' => '2019-01-15',
                'end_date' => '2019-01-30',
                'hour_amount' => 0,
                'other_amount' => 0,
                'is_optional' => true,
                'is_interrupted' => false
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
            ->notSeeInDatabase('stages', ['id' => 5]);
        $this->json('POST',
            '/students/1/stages',
            [
                'location' => 'Location 1',
                'sub_location' => 'Sub-location 1',
                'start_date' => '2019-01-05',
                'end_date' => '2019-01-10',
                'hour_amount' => 0,
                'other_amount' => 0,
                'is_optional' => true,
                'is_interrupted' => false
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
            ->notSeeInDatabase('stages', ['id' => 5]);
        $this->json('POST',
            '/students/1/stages',
            [
                'location' => 'Location 1',
                'sub_location' => 'Sub-location 1',
                'start_date' => '2019-01-24',
                'end_date' => '2019-01-30',
                'hour_amount' => 0,
                'other_amount' => 0,
                'is_optional' => true,
                'is_interrupted' => false
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
            ->notSeeInDatabase('stages', ['id' => 5]);

        // Unallowed additional property.
        $this->json('POST',
            '/students/1/stages',
            [
                'location' => 'Location 1',
                'sub_location' => 'Sub-location 1',
                'start_date' => '2019-02-25',
                'end_date' => '2019-02-28',
                'hour_amount' => 0,
                'other_amount' => 0,
                'is_optional' => true,
                'is_interrupted' => false,
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
            ->notSeeInDatabase('stages', ['id' => 5]);
            
        // Missing required location
        $this->json('POST',
            '/students/1/stages',
            [
                'start_date' => '2019-02-25',
                'end_date' => '2019-02-28',
                'hour_amount' => 0,
                'other_amount' => 0,
                'is_optional' => true,
                'is_interrupted' => false,
            ]
        )
            ->seeJsonEquals([
                'status_code' => 400,
                'status' => 'Bad Request',
                'message' => 'Request is not valid',
                'data' => [
                    'location' => [
                        'code error_required',
                        'in body',
                    ]
                ]
            ])
            ->seeStatusCode(400)
            ->notSeeInDatabase('stages', ['id' => 5]);
            
        // Missing required start_date
        $this->json('POST',
            '/students/1/stages',
            [
                'location' => 'Location 1',
                'end_date' => '2019-02-28',
                'hour_amount' => 0,
                'other_amount' => 0,
                'is_optional' => true,
                'is_interrupted' => false,
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
            ->notSeeInDatabase('stages', ['id' => 5]);
            
        // Missing required end_date
        $this->json('POST',
            '/students/1/stages',
            [
                'location' => 'Location 1',
                'start_date' => '2019-02-25',
                'hour_amount' => 0,
                'other_amount' => 0,
                'is_optional' => true,
                'is_interrupted' => false,
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
            ->notSeeInDatabase('stages', ['id' => 5]);

        // Missing required hour_amount
        $this->json('POST',
            '/students/1/stages',
            [
                'location' => 'Location 1',
                'start_date' => '2019-02-25',
                'end_date' => '2019-02-28',
                'other_amount' => 0,
                'is_optional' => true,
                'is_interrupted' => false,
            ]
        )
            ->seeJsonEquals([
                'status_code' => 400,
                'status' => 'Bad Request',
                'message' => 'Request is not valid',
                'data' => [
                    'hour_amount' => [
                        'code error_required',
                        'in body',
                    ]
                ]
            ])
            ->seeStatusCode(400)
            ->notSeeInDatabase('stages', ['id' => 5]);

        // Missing required other_amount
        $this->json('POST',
            '/students/1/stages',
            [
                'location' => 'Location 1',
                'start_date' => '2019-02-25',
                'end_date' => '2019-02-28',
                'hour_amount' => 0,
                'is_optional' => true,
                'is_interrupted' => false,
            ]
        )
            ->seeJsonEquals([
                'status_code' => 400,
                'status' => 'Bad Request',
                'message' => 'Request is not valid',
                'data' => [
                    'other_amount' => [
                        'code error_required',
                        'in body',
                    ]
                ]
            ])
            ->seeStatusCode(400)
            ->notSeeInDatabase('stages', ['id' => 5]);

        // Missing required is_optional
        $this->json('POST',
            '/students/1/stages',
            [
                'location' => 'Location 1',
                'start_date' => '2019-02-25',
                'end_date' => '2019-02-28',
                'hour_amount' => 0,
                'other_amount' => 0,
                'is_interrupted' => false,
            ]
        )
            ->seeJsonEquals([
                'status_code' => 400,
                'status' => 'Bad Request',
                'message' => 'Request is not valid',
                'data' => [
                    'is_optional' => [
                        'code error_required',
                        'in body',
                    ]
                ]
            ])
            ->seeStatusCode(400)
            ->notSeeInDatabase('stages', ['id' => 5]);
            
        // @todo add further tests related to invalid attribute format

    }

    /**
     * Modify a stage: success.
     */
    public function testModifyById()
    {

        $this->json('PUT',
            '/stages/1',
            [
                'location' => 'Location 1',
                'sub_location' => 'Sub-location 1',
                'start_date' => '2019-01-10',
                'end_date' => '2019-01-24',
                'hour_amount' => 456, // --> modified
                'other_amount' => 7,  // --> modified
                'is_optional' => false,
                'is_interrupted' => false
            ]
        )
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource successfully retrieved/created/modified',
                'data' => [
                    'id' => 1,
                    'location' => 'Location 1',
                    'sub_location' => 'Sub-location 1',
                    'student' => [
                        'id' => 1,
                        'first_name' => 'John',
                        'last_name' => 'Doe',
                        'e_mail' => 'john.doe@foo.com',
                        'phone' => '1234-567890',
                        'nationality' => 'GB',
                    ],
                    'start_date' => '2019-01-10',
                    'end_date' => '2019-01-24',
                    'hour_amount' => 456,
                    'other_amount' => 7,
                    'is_optional' => false,
                    'is_interrupted' => false,
                ],
            ])
            ->seeStatusCode(200)
            ->seeInDatabase('stages', ['id' => 1, 'hour_amount' => 456, 'other_amount' => 7])
            ->notSeeInDatabase('stages', ['id' => 1, 'hour_amount' => 123, 'other_amount' => 5])
            ->notSeeInDatabase('stages', ['id' => 5]);
/* @todo check
        // Remove sub-location, amounts and is_interrupted
        $this->json('PUT',
            '/stages/1',
            [
                'location' => 'Location 1',
                'start_date' => '2019-01-10',
                'end_date' => '2019-01-24',
                'is_optional' => false,
            ]
        )
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource successfully retrieved/created/modified',
                'data' => [
                    'id' => 1,
                    'location' => 'Location 1',
                    'student' => [
                        'id' => 1,
                        'first_name' => 'John',
                        'last_name' => 'Doe',
                        'e_mail' => 'john.doe@foo.com',
                        'phone' => '1234-567890',
                        'nationality' => 'GB',
                    ],
                    'start_date' => '2019-01-10',
                    'end_date' => '2019-01-24',
                    'is_optional' => false,
                ],
            ])
            ->seeStatusCode(200)
            ->seeInDatabase('stages', ['id' => 1, 'hour_amount' => null, 'other_amount' => null, 'is_interrupted' => null, 'sub_location_id' => null])
            ->notSeeInDatabase('stages', ['id' => 1, 'hour_amount' => 456, 'other_amount' => 7,  'is_interrupted' => 0, 'sub_location_id' => 1])
            ->notSeeInDatabase('stages', ['id' => 5]);
            
        // Add sub-location, amounts and is_interrupted
        $this->json('PUT',
            '/stages/1',
            [
                'location' => 'Location 1',
                'sub_location' => 'Sub-location 1',
                'start_date' => '2019-01-10',
                'end_date' => '2019-01-24',
                'hour_amount' => 456,
                'other_amount' => 7,
                'is_optional' => false,
                'is_interrupted' => false
            ]
        )
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource successfully retrieved/created/modified',
                'data' => [
                    'id' => 1,
                    'location' => 'Location 1',
                    'sub_location' => 'Sub-location 1',
                    'student' => [
                        'id' => 1,
                        'first_name' => 'John',
                        'last_name' => 'Doe',
                        'e_mail' => 'john.doe@foo.com',
                        'phone' => '1234-567890',
                        'nationality' => 'GB',
                    ],
                    'start_date' => '2019-01-10',
                    'end_date' => '2019-01-24',
                    'hour_amount' => 456,
                    'other_amount' => 7,
                    'is_optional' => false,
                    'is_interrupted' => false,
                ],
            ])
            ->seeStatusCode(200)
            ->notSeeInDatabase('stages', ['id' => 1, 'hour_amount' => null, 'other_amount' => null, 'is_interrupted' => null, 'sub_location_id' => null])
            ->seeInDatabase('stages', ['id' => 1, 'hour_amount' => 456, 'other_amount' => 7,  'is_interrupted' => 0, 'sub_location_id' => 1])
            ->notSeeInDatabase('stages', ['id' => 5]);
*/
    }

    /**
     * Modify a stage: failure.
     */
    public function testModifyByIdFailure()
    {

        // Non existing ID
        $this->json('PUT',
            '/stages/999',
            [
                'location' => 'Location 1',
                'sub_location' => 'Sub-location 1',
                'start_date' => '2019-01-10',
                'end_date' => '2019-01-24',
                'hour_amount' => 456, // --> modified
                'other_amount' => 7,  // --> modified
                'is_optional' => false,
                'is_interrupted' => false
            ]
        )
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ])
            ->seeStatusCode(404)
            ->notSeeInDatabase('stages', ['id' => 999])
            ->notSeeInDatabase('stages', ['id' => 5]);

        // Invalid ID
        $this->json('PUT',
            '/stages/abc',
            [
                'location' => 'Location 1',
                'sub_location' => 'Sub-location 1',
                'start_date' => '2019-01-10',
                'end_date' => '2019-01-24',
                'hour_amount' => 456, // --> modified
                'other_amount' => 7,  // --> modified
                'is_optional' => false,
                'is_interrupted' => false
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
            ->notSeeInDatabase('stages', ['id' => 'abc'])
            ->notSeeInDatabase('stages', ['id' => 5]);

        // The record created by this method is used by below tests.
        $this->testCreateRelatedToStudent();

        // Overlapping time range
        $this->json('PUT',
            '/stages/5',
            [
                'location' => 'Location 1',
                'sub_location' => 'Sub-location 1',
                'start_date' => '2019-01-20',
                'end_date' => '2019-01-31',
                'hour_amount' => 0,
                'other_amount' => 0,
                'is_optional' => true,
                'is_interrupted' => false
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
            ->seeInDatabase('stages', ['id' => 5, 'start_date' => '2019-02-01'])
            ->notSeeInDatabase('stages', ['id' => 5, 'start_date' => '2019-01-20']);

        // Switched dates
        $this->json('PUT',
            '/stages/5',
            [
                'location' => 'Location 1',
                'sub_location' => 'Sub-location 1',
                'start_date' => '2019-01-25',
                'end_date' => '2019-01-21',
                'hour_amount' => 0,
                'other_amount' => 0,
                'is_optional' => true,
                'is_interrupted' => false
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
            ->seeInDatabase('stages', ['id' => 5, 'start_date' => '2019-02-01', 'end_date' => '2019-02-11'])
            ->notSeeInDatabase('stages', ['id' => 5, 'start_date' => '2019-01-25', 'end_date' => '2019-01-21']);

        // Unallowed additional property.
        $this->json('PUT',
            '/stages/1',
            [
                'location' => 'Location 1',
                'sub_location' => 'Sub-location 1',
                'start_date' => '2019-01-10',
                'end_date' => '2019-01-24',
                'hour_amount' => 111, // --> modified
                'other_amount' => 5,
                'is_optional' => true, // --> modified
                'is_interrupted' => false,
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
            ->seeInDatabase('stages', ['id' => 1])
            ->notSeeInDatabase('stages', ['id' => 1, 'hour_amount' => 111, 'is_optional' => true]);

        // If interruption report is available, "is_interrupted" cannot be switched from true to false.
        $this->json('PUT',
            '/stages/2',
            [
                'location' => 'Location 1',
                'sub_location' => 'Sub-location 1',
                'start_date' => '2019-01-26',
                'end_date' => '2019-01-31',
                'hour_amount' => 34,
                'other_amount' => 0,
                'is_optional' => true,
                'is_interrupted' => false, // --> modified
            ]
        )
            ->seeJsonEquals([
                'status_code' => 400,
                'status' => 'Bad Request',
                'message' => 'Request is not valid',
                'data' => [
                    'is_interrupted' => [
                        'Stage actually has interruption report',
                    ]
                ]
            ])
            ->seeInDatabase('stages', ['id' => 2])
            ->notSeeInDatabase('stages', ['id' => 2, 'is_interrupted' => false]);

        // @todo add further tests related to missing required fields
        // @todo add further tests related to invalid attribute format

    }

    /**
     * Delete a stage: success.
     */
    public function testDeleteById()
    {

        // Existing stage
        $this->json('DELETE', '/stages/3')
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource deleted',
            ])
            ->seeStatusCode(200)
            ->seeInDatabase('stages', ['id' => 3, 'deleted_at' => date('Y-m-d H:i:s')])
            ->notSeeInDatabase('stages', ['id' => 3, 'deleted_at' => null]);

    }

    /**
     * Delete a stage: failure.
     */
    public function testDeleteByIdFailure()
    {

        // Non existing stage
        $this->json('DELETE', '/stages/999')
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ])
            ->seeStatusCode(404)
            ->notSeeInDatabase('stages', ['id' => 999]);

        // Invalid ID
        $this->json('DELETE', '/stages/abc')
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
            ->notSeeInDatabase('stages', ['id' => 'abc']);

        // Stage with evaluation
        $this->json('DELETE', '/stages/1')
            ->seeJsonEquals([
                'status_code' => 400,
                'status' => 'Bad Request',
                'message' => 'Request is not valid',
                'data' => [
                    'stage_id' => [
                        'Stage actually has evaluation and/or interruption report',
                    ],
                ]
            ])
            ->seeStatusCode(400)
            ->seeInDatabase('stages', ['id' => 1, 'deleted_at' => null]);

        // Stage with interruption report
        $this->json('DELETE', '/stages/2')
            ->seeJsonEquals([
                'status_code' => 400,
                'status' => 'Bad Request',
                'message' => 'Request is not valid',
                'data' => [
                    'stage_id' => [
                        'Stage actually has evaluation and/or interruption report',
                    ],
                ]
            ])
            ->seeStatusCode(400)
            ->seeInDatabase('stages', ['id' => 2, 'deleted_at' => null]);

    }

}

<?php
declare(strict_types = 1);

class InternshipsTest extends TestCase
{

    /**
     * Get all internships.
     *
     * @throws Exception
     */
    public function testGet()
    {

        $this->json('GET', '/internships')
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource(s) found',
                'data' => [
                    [
                        'id' => 1,
                        'location' => 'Location 1',
                        'sub_location' => 'Sub-location 1',
                        'student' => (new StudentBuilder('john'))->build(),
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
                        'student' => (new StudentBuilder('john'))->build(),
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
                        'student' => (new StudentBuilder('joan'))->build(),
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
                        'student' => (new StudentBuilder('joan'))->build(),
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
     * Get internship by ID.
     */
    public function testGetById()
    {

        // Existing
        $this->json('GET', '/internships/1')
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource successfully retrieved/created/modified',
                'data' => [
                    'id' => 1,
                    'location' => 'Location 1',
                    'sub_location' => 'Sub-location 1',
                    'student' => (new StudentBuilder('john'))->build(),
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
     * Get internship by ID: failure.
     */
    public function testGetByIdFailure()
    {

        // Non existing
        $this->json('GET', '/internships/9999')
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ])
            ->seeStatusCode(404);

        // Invalid ID
        $this->json('GET', '/internships/abc')
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
     * Get student's internships: success.
     */
    public function testGetRelatedToStudent()
    {

        // Existing
        $this->json('GET', '/students/1/internships')
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
     * Get student's internships: failure.
     */
    public function testGetRelatedToStudentFailure()
    {

        // Non existing internships
        $this->json('GET', '/students/2/internships')
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ])
            ->seeStatusCode(404);

        // Non existing student
        $this->json('GET', '/students/999/internships')
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ])
            ->seeStatusCode(404);

        // Invalid student ID
        $this->json('GET', '/students/abc/internships')
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
     * Create a student's internship: success.
     */
    public function testCreateRelatedToStudent()
    {

        // Existing student, with sub-location
        $this->json('POST',
            '/students/1/internships',
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
            ->seeInDatabase('internships', ['id' => 5, 'student_id' => 1, 'location_id' => 1, 'sub_location_id' => 1])
            ->notSeeInDatabase('internships', ['id' => 6]);

        // Existing student, without sub-location
        $this->json('POST',
            '/students/1/internships',
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
            ->seeInDatabase('internships', ['id' => 6, 'student_id' => 1, 'location_id' => 1, 'sub_location_id' => null])
            ->notSeeInDatabase('internships', ['id' => 7]);

    }

    /**
     * Create a student's internship: failure.
     */
    public function testCreateRelatedToStudentFailure()
    {

        // Non existing student
        $this->json('POST',
            '/students/999/internships',
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
            ->notSeeInDatabase('internships', ['id' => 5])
            ->notSeeInDatabase('internships', ['student_id' => 999]);

        // Invalid student ID
        $this->json('POST',
            '/students/abc/internships',
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
            ->notSeeInDatabase('internships', ['id' => 5])
            ->notSeeInDatabase('internships', ['student_id' => 'abc']);

        // Non existing location
        $this->json('POST',
            '/students/1/internships',
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
            ->notSeeInDatabase('internships', ['id' => 5]);

        // Non existing sub-location
        $this->json('POST',
            '/students/1/internships',
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
            ->notSeeInDatabase('internships', ['id' => 5]);

        // Switched dates
        $this->json('POST',
            '/students/1/internships',
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
            ->notSeeInDatabase('internships', ['id' => 5])
            ->notSeeInDatabase('internships', ['start_date' => '2019-01-31', 'end_date' => '2019-01-25']);

        // Identical dates
        $this->json('POST',
            '/students/1/internships',
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
            ->notSeeInDatabase('internships', ['id' => 5])
            ->notSeeInDatabase('internships', ['start_date' => '2019-01-25', 'end_date' => '2019-01-25']);

        // Overlapping time range
        $this->json('POST',
            '/students/1/internships',
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
            ->notSeeInDatabase('internships', ['id' => 5]);
        $this->json('POST',
            '/students/1/internships',
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
            ->notSeeInDatabase('internships', ['id' => 5]);
        $this->json('POST',
            '/students/1/internships',
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
            ->notSeeInDatabase('internships', ['id' => 5]);
        $this->json('POST',
            '/students/1/internships',
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
            ->notSeeInDatabase('internships', ['id' => 5]);

        // Unallowed additional property.
        $this->json('POST',
            '/students/1/internships',
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
            ->notSeeInDatabase('internships', ['id' => 5]);

        // Missing required location
        $this->json('POST',
            '/students/1/internships',
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
            ->notSeeInDatabase('internships', ['id' => 5]);

        // Missing required start_date
        $this->json('POST',
            '/students/1/internships',
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
            ->notSeeInDatabase('internships', ['id' => 5]);

        // Missing required end_date
        $this->json('POST',
            '/students/1/internships',
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
            ->notSeeInDatabase('internships', ['id' => 5]);

        // Missing required hour_amount
        $this->json('POST',
            '/students/1/internships',
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
            ->notSeeInDatabase('internships', ['id' => 5]);

        // Missing required other_amount
        $this->json('POST',
            '/students/1/internships',
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
            ->notSeeInDatabase('internships', ['id' => 5]);

        // Missing required is_optional
        $this->json('POST',
            '/students/1/internships',
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
            ->notSeeInDatabase('internships', ['id' => 5]);

        // @todo add further tests related to invalid attribute format
        // @todo if eligibilities are enforced (config parameter), check if valid time range

    }

    /**
     * Modify an internship: success.
     */
    public function testModifyById()
    {

        $this->json('PUT',
            '/internships/1',
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
                    'student' => (new StudentBuilder('john'))->build(),
                    'start_date' => '2019-01-10',
                    'end_date' => '2019-01-24',
                    'hour_amount' => 456,
                    'other_amount' => 7,
                    'is_optional' => false,
                    'is_interrupted' => false,
                ],
            ])
            ->seeStatusCode(200)
            ->seeInDatabase('internships', ['id' => 1, 'hour_amount' => 456, 'other_amount' => 7])
            ->notSeeInDatabase('internships', ['id' => 1, 'hour_amount' => 123, 'other_amount' => 5])
            ->notSeeInDatabase('internships', ['id' => 5]);

        // Remove sub-location
        $this->json('PUT',
            '/internships/1',
            [
                'location' => 'Location 1',
                'start_date' => '2019-01-10',
                'end_date' => '2019-01-24',
                'hour_amount' => 456,
                'other_amount' => 7,
                'is_optional' => false,
                'is_interrupted' => false,
            ]
        )
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource successfully retrieved/created/modified',
                'data' => [
                    'id' => 1,
                    'location' => 'Location 1',
                    'student' => (new StudentBuilder('john'))->build(),
                    'start_date' => '2019-01-10',
                    'end_date' => '2019-01-24',
                    'hour_amount' => 456,
                    'other_amount' => 7,
                    'is_optional' => false,
                    'is_interrupted' => false,
                ],
            ])
            ->seeStatusCode(200)
            ->seeInDatabase('internships', ['id' => 1, 'sub_location_id' => null])
            ->notSeeInDatabase('internships', ['id' => 1, 'sub_location_id' => 1])
            ->notSeeInDatabase('internships', ['id' => 5]);

        // Re-add sub-location
        $this->json('PUT',
            '/internships/1',
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
                    'student' => (new StudentBuilder('john'))->build(),
                    'start_date' => '2019-01-10',
                    'end_date' => '2019-01-24',
                    'hour_amount' => 456,
                    'other_amount' => 7,
                    'is_optional' => false,
                    'is_interrupted' => false,
                ],
            ])
            ->seeStatusCode(200)
            ->seeInDatabase('internships', ['id' => 1, 'sub_location_id' => 1])
            ->notSeeInDatabase('internships', ['id' => 1, 'sub_location_id' => null])
            ->notSeeInDatabase('internships', ['id' => 5]);

    }

    /**
     * Modify an internship: failure.
     */
    public function testModifyByIdFailure()
    {

        // Non existing ID
        $this->json('PUT',
            '/internships/999',
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
            ->notSeeInDatabase('internships', ['id' => 999])
            ->notSeeInDatabase('internships', ['id' => 5]);

        // Invalid ID
        $this->json('PUT',
            '/internships/abc',
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
            ->notSeeInDatabase('internships', ['id' => 'abc'])
            ->notSeeInDatabase('internships', ['id' => 5]);

        // The record created by this method is used by below tests.
        $this->testCreateRelatedToStudent();

        // Overlapping time range
        $this->json('PUT',
            '/internships/5',
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
            ->seeInDatabase('internships', ['id' => 5, 'start_date' => '2019-02-01'])
            ->notSeeInDatabase('internships', ['id' => 5, 'start_date' => '2019-01-20']);

        // Switched dates
        $this->json('PUT',
            '/internships/5',
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
            ->seeInDatabase('internships', ['id' => 5, 'start_date' => '2019-02-01', 'end_date' => '2019-02-11'])
            ->notSeeInDatabase('internships', ['id' => 5, 'start_date' => '2019-01-25', 'end_date' => '2019-01-21']);

        // Unallowed additional property.
        $this->json('PUT',
            '/internships/1',
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
            ->seeInDatabase('internships', ['id' => 1])
            ->notSeeInDatabase('internships', ['id' => 1, 'hour_amount' => 111, 'is_optional' => true]);

        // If interruption report is available, "is_interrupted" cannot be switched from true to false.
        $this->json('PUT',
            '/internships/2',
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
                        'Internship actually has interruption report',
                    ]
                ]
            ])
            ->seeInDatabase('internships', ['id' => 2, 'is_interrupted' => true]);

        // Missing required location
        $this->json('PUT',
            '/internships/3',
            [
                'sub_location' => 'Sub-location 1',
                'start_date' => '2019-01-26',
                'end_date' => '2019-01-31',
                'hour_amount' => 34,
                'other_amount' => 0,
                'is_optional' => false,
                'is_interrupted' => true
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
            ->seeStatusCode(400);

        // Missing required start_date
        $this->json('PUT',
            '/internships/3',
            [
                'location' => 'Location 1',
                'sub_location' => 'Sub-location 1',
                'end_date' => '2019-01-31',
                'hour_amount' => 34,
                'other_amount' => 0,
                'is_optional' => false,
                'is_interrupted' => true
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
            ->seeStatusCode(400);

        // Missing required end_date
        $this->json('PUT',
            '/internships/3',
            [
                'location' => 'Location 1',
                'sub_location' => 'Sub-location 1',
                'start_date' => '2019-01-26',
                'hour_amount' => 34,
                'other_amount' => 0,
                'is_optional' => false,
                'is_interrupted' => true
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
            ->seeStatusCode(400);

        // Missing required hour_amount
        $this->json('PUT',
            '/internships/3',
            [
                'location' => 'Location 1',
                'sub_location' => 'Sub-location 1',
                'start_date' => '2019-01-26',
                'end_date' => '2019-01-31',
                'other_amount' => 0,
                'is_optional' => false,
                'is_interrupted' => true
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
            ->seeStatusCode(400);

        // Missing required other_amount
        $this->json('PUT',
            '/internships/3',
            [
                'location' => 'Location 1',
                'sub_location' => 'Sub-location 1',
                'start_date' => '2019-01-26',
                'end_date' => '2019-01-31',
                'hour_amount' => 34,
                'is_optional' => false,
                'is_interrupted' => true
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
            ->seeStatusCode(400);

        // Missing required is_optional
        $this->json('PUT',
            '/internships/3',
            [
                'location' => 'Location 1',
                'sub_location' => 'Sub-location 1',
                'start_date' => '2019-01-26',
                'end_date' => '2019-01-31',
                'hour_amount' => 34,
                'other_amount' => 0,
                'is_interrupted' => true
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
            ->seeStatusCode(400);

        // Missing required is_interrupted
        $this->json('PUT',
            '/internships/3',
            [
                'location' => 'Location 1',
                'sub_location' => 'Sub-location 1',
                'start_date' => '2019-01-26',
                'end_date' => '2019-01-31',
                'hour_amount' => 34,
                'other_amount' => 0,
                'is_optional' => false,
            ]
        )
            ->seeJsonEquals([
                'status_code' => 400,
                'status' => 'Bad Request',
                'message' => 'Request is not valid',
                'data' => [
                    'is_interrupted' => [
                        'code error_required',
                        'in body',
                    ]
                ]
            ])
            ->seeStatusCode(400);

        // @todo add further tests related to invalid attribute format
        // @todo if eligibilities are enforced (config parameter), check if valid time range

    }

    /**
     * Delete an internship: success.
     */
    public function testDeleteById()
    {

        // Existing internship
        $this->json('DELETE', '/internships/3')
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource deleted',
            ])
            ->seeStatusCode(200)
            ->notSeeInDatabase('internships', ['id' => 3]);

    }

    /**
     * Delete an internship: failure.
     */
    public function testDeleteByIdFailure()
    {

        // Non existing internship
        $this->json('DELETE', '/internships/999')
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ])
            ->seeStatusCode(404)
            ->notSeeInDatabase('internships', ['id' => 999]);

        // Invalid ID
        $this->json('DELETE', '/internships/abc')
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
            ->notSeeInDatabase('internships', ['id' => 'abc']);

        // Internship with evaluation
        $this->json('DELETE', '/internships/1')
            ->seeJsonEquals([
                'status_code' => 400,
                'status' => 'Bad Request',
                'message' => 'Request is not valid',
                'data' => [
                    'internship_id' => [
                        'Internship actually has evaluation and/or interruption report',
                    ],
                ]
            ])
            ->seeStatusCode(400)
            ->seeInDatabase('internships', ['id' => 1]);

        // Internship with interruption report
        $this->json('DELETE', '/internships/2')
            ->seeJsonEquals([
                'status_code' => 400,
                'status' => 'Bad Request',
                'message' => 'Request is not valid',
                'data' => [
                    'internship_id' => [
                        'Internship actually has evaluation and/or interruption report',
                    ],
                ]
            ])
            ->seeStatusCode(400)
            ->seeInDatabase('internships', ['id' => 2]);

    }

}

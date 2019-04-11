<?php
declare(strict_types = 1);

class EligibilitiesTest extends TestCase
{

    /**
     * Get all eligibilities.
     */
    public function testGet()
    {
        $this->json('GET', '/eligibilities')
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource(s) found',
                'data' => [
                    [
                        'id' => 2,
                        'start_date' => '2019-01-01',
                        'end_date' => '2019-12-01',
                        'notes' => 'First eligibility notes',
                        'is_eligible' => true,
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
                        'start_date' => '2019-01-01',
                        'end_date' => '2019-12-01',
                        'is_eligible' => false,
                        'student' => [
                            'id' => 2,
                            'first_name' => 'Jane',
                            'last_name' => 'Doe',
                            'e_mail' => 'jane.doe@bar.com',
                            'nationality' => 'CA',
                        ],
                    ]
                ]
            ])
            ->seeStatusCode(200);
    }

    /**
     * Get eligibility by ID.
     */
    public function testGetById()
    {

        // Existing
        $this->json('GET', '/eligibilities/2')
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource successfully retrieved/created/modified',
                'data' => [
                    'id' => 2,
                    'start_date' => '2019-01-01',
                    'end_date' => '2019-12-01',
                    'notes' => 'First eligibility notes',
                    'is_eligible' => true,
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
     * Get eligibility by ID: failure.
     */
    public function testGetByIdFailure()
    {

        // Deleted
        $this->json('GET', '/eligibilities/1')
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ])
            ->seeStatusCode(404);

        // Non existing
        $this->json('GET', '/eligibilities/9999')
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ])
            ->seeStatusCode(404);

        // Invalid ID
        $this->json('GET', '/eligibilities/abc')
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
     * Get student's eligibilities: success.
     */
    public function testGetRelatedToStudent()
    {

        // Existing
        $this->json('GET', '/students/1/eligibilities')
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource(s) found',
                'data' => [
                    [
                        'id' => 2,
                        'start_date' => '2019-01-01',
                        'end_date' => '2019-12-01',
                        'notes' => 'First eligibility notes',
                        'is_eligible' => true,
                    ]
                ],
            ])
            ->seeStatusCode(200);

    }

    /**
     * Get student's eligibilities: failure.
     */
    public function testGetRelatedToStudentFailure()
    {

        // Non existing eligibilities
        $this->json('GET', '/students/4/eligibilities')
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ])
            ->seeStatusCode(404);

        // Non existing student
        $this->json('GET', '/students/999/eligibilities')
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ])
            ->seeStatusCode(404);

        // Deleted student
        $this->json('GET', '/students/3/eligibilities')
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ])
            ->seeStatusCode(404);

        // Invalid student ID
        $this->json('GET', '/students/abc/eligibilities')
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
     * Create a student's eligibility.
     */
    public function testCreateRelatedToStudent()
    {

        // Existing student
        $this->json('POST',
            '/students/1/eligibilities',
            [
                'start_date' => '2020-01-01',
                'end_date' => '2020-12-01',
                'notes' => 'Another eligibility notes',
                'is_eligible' => true,
            ]
        )
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource successfully retrieved/created/modified',
                'data' => [
                    'id' => 4,
                    'start_date' => '2020-01-01',
                    'end_date' => '2020-12-01',
                    'notes' => 'Another eligibility notes',
                    'is_eligible' => true,
                ],
            ])
            ->seeStatusCode(200)
            ->seeInDatabase('eligibilities', ['id' => 4, 'student_id' => 1])
            ->notSeeInDatabase('eligibilities', ['id' => 5]);

        // Existing student, without notes
        $this->json('POST',
            '/students/2/eligibilities',
            [
                'start_date' => '2020-01-01',
                'end_date' => '2020-12-01',
                'is_eligible' => true,
            ]
        )
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource successfully retrieved/created/modified',
                'data' => [
                    'id' => 5,
                    'start_date' => '2020-01-01',
                    'end_date' => '2020-12-01',
                    'is_eligible' => true,
                ],
            ])
            ->seeStatusCode(200)
            ->seeInDatabase('eligibilities', ['id' => 5, 'student_id' => 2, 'notes' => null])
            ->notSeeInDatabase('eligibilities', ['id' => 6]);

    }

    /**
     * Create a student's eligibility: failure.
     */
    public function testCreateRelatedToStudentFailure()
    {

        // Non existing student
        $this->json('POST',
            '/students/999/eligibilities',
            [
                'start_date' => '2020-01-01',
                'end_date' => '2020-12-01',
                'notes' => 'Another eligibility notes',
                'is_eligible' => true,
            ]
        )
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ])
            ->seeStatusCode(404)
            ->notSeeInDatabase('eligibilities', ['id' => 4])
            ->notSeeInDatabase('eligibilities', ['student_id' => 999]);

        // Deleted student
        $this->json('POST',
            '/students/3/eligibilities',
            [
                'start_date' => '2020-01-01',
                'end_date' => '2020-12-01',
                'notes' => 'Another eligibility notes',
                'is_eligible' => true,
            ]
        )
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ])
            ->seeStatusCode(404)
            ->notSeeInDatabase('eligibilities', ['id' => 4])
            ->notSeeInDatabase('eligibilities', ['student_id' => 999]);

        // Invalid student ID
        $this->json('POST',
            '/students/abc/eligibilities',
            [
                'start_date' => '2020-01-01',
                'end_date' => '2020-12-01',
                'notes' => 'Another eligibility notes',
                'is_eligible' => true,
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
            ->notSeeInDatabase('eligibilities', ['id' => 4])
            ->notSeeInDatabase('eligibilities', ['student_id' => 'abc']);

        // Missing required start_date
        $this->json('POST',
            '/students/1/eligibilities',
            [
                'end_date' => '2020-12-01',
                'notes' => 'Another eligibility notes',
                'is_eligible' => true,
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
            ->notSeeInDatabase('eligibilities', ['id' => 4]);

        // Missing required end_date
        $this->json('POST',
            '/students/1/eligibilities',
            [
                'start_date' => '2020-01-01',
                'notes' => 'Another eligibility notes',
                'is_eligible' => true,
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
            ->notSeeInDatabase('eligibilities', ['id' => 4]);

        // Missing required is_eligible
        $this->json('POST',
            '/students/1/eligibilities',
            [
                'start_date' => '2020-01-01',
                'end_date' => '2020-12-01',
                'notes' => 'Another eligibility notes',
            ]
        )
            ->seeJsonEquals([
                'status_code' => 400,
                'status' => 'Bad Request',
                'message' => 'Request is not valid',
                'data' => [
                    'is_eligible' => [
                        'code error_required',
                        'in body',
                    ]
                ]
            ])
            ->seeStatusCode(400)
            ->notSeeInDatabase('eligibilities', ['id' => 4]);

        // Overlapping dates - 1
        $this->json('POST',
            '/students/1/eligibilities',
            [
                'start_date' => '2019-02-01',
                'end_date' => '2019-03-01',
                'is_eligible' => true,
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
            ->notSeeInDatabase('eligibilities', ['id' => 4]);

        // Overlapping dates - 2
        $this->json('POST',
            '/students/1/eligibilities',
            [
                'start_date' => '2018-12-01',
                'end_date' => '2019-03-01',
                'is_eligible' => true,
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
            ->notSeeInDatabase('eligibilities', ['id' => 4]);

        // Overlapping dates - 3
        $this->json('POST',
            '/students/1/eligibilities',
            [
                'start_date' => '2019-02-01',
                'end_date' => '2020-03-01',
                'is_eligible' => true,
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
            ->notSeeInDatabase('eligibilities', ['id' => 4]);

        // Identical dates
        $this->json('POST',
            '/students/1/eligibilities',
            [
                'start_date' => '2020-03-01',
                'end_date' => '2020-03-01',
                'is_eligible' => true,
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
            ->notSeeInDatabase('eligibilities', ['id' => 4]);

        // Switched dates
        $this->json('POST',
            '/students/1/eligibilities',
            [
                'start_date' => '2020-04-01',
                'end_date' => '2020-03-01',
                'is_eligible' => true,
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
            ->notSeeInDatabase('eligibilities', ['id' => 4]);

        // Unallowed additional property.
        $this->json('POST',
            '/students/1/eligibilities',
            [
                'start_date' => '2020-02-01',
                'end_date' => '2020-03-01',
                'is_eligible' => true,
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
            ->notSeeInDatabase('eligibilities', ['id' => 4]);

        // @todo add further tests related to invalid attribute format

    }

    /**
     * Modify an eligibility: success.
     */
    public function testModifyById()
    {

        // Success
        $this->json('PUT',
            '/eligibilities/2',
            [
                'start_date' => '2019-01-01',
                'end_date' => '2019-12-01',
                'notes' => 'First eligibility notes modified', // --> modified
                'is_eligible' => true,
            ]
        )
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource successfully retrieved/created/modified',
                'data' => [
                    'id' => 2,
                    'start_date' => '2019-01-01',
                    'end_date' => '2019-12-01',
                    'notes' => 'First eligibility notes modified', // --> modified
                    'is_eligible' => true,
                    'student' => [
                        'id' => 1,
                        'first_name' => 'John',
                        'last_name' => 'Doe',
                        'e_mail' => 'john.doe@foo.com',
                        'phone' => '1234-567890',
                        'nationality' => 'GB',
                    ],
                ]
            ])
            ->seeStatusCode(200)
            ->seeInDatabase('eligibilities', ['id' => 2, 'notes' => 'First eligibility notes modified'])
            ->notSeeInDatabase('eligibilities', ['id' => 2, 'notes' => 'First eligibility notes']);

        // Success, remove notes and change start date
        $this->json('PUT',
            '/eligibilities/2',
            [
                'start_date' => '2019-02-01', // --> modified
                'end_date' => '2019-12-01',
                'is_eligible' => true,
            ]
        )
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource successfully retrieved/created/modified',
                'data' => [
                    'id' => 2,
                    'start_date' => '2019-02-01', // --> modified
                    'end_date' => '2019-12-01',
                    'is_eligible' => true,
                    'student' => [
                        'id' => 1,
                        'first_name' => 'John',
                        'last_name' => 'Doe',
                        'e_mail' => 'john.doe@foo.com',
                        'phone' => '1234-567890',
                        'nationality' => 'GB',
                    ],
                ]
            ])
            ->seeStatusCode(200)
            ->seeInDatabase('eligibilities', ['id' => 2, 'start_date' => '2019-02-01', 'notes' => null])
            ->notSeeInDatabase('eligibilities', ['id' => 2, 'notes' => 'First eligibility notes modified'])
            ->notSeeInDatabase('eligibilities', ['id' => 2, 'start_date' => '2019-01-01']);

    }

    /**
     * Modify an eligibility: failure.
     */
    public function testModifyByIdFailure()
    {

        // Invalid ID
        $this->json('PUT',
            '/eligibilities/abc',
            [
                'start_date' => '2019-01-01',
                'end_date' => '2019-12-01',
                'notes' => 'Modified eligibility notes',
                'is_eligible' => true,
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
            ->notSeeInDatabase('eligibilities', ['id' => 'abc'])
            ->notSeeInDatabase('eligibilities', ['id' => 4]);

        // Non existing ID
        $this->json('PUT',
            '/eligibilities/999',
            [
                'start_date' => '2019-01-01',
                'end_date' => '2019-12-01',
                'notes' => 'Modified eligibility notes',
                'is_eligible' => true,
            ]
        )
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ])
            ->seeStatusCode(404)
            ->notSeeInDatabase('eligibilities', ['id' => 999])
            ->notSeeInDatabase('eligibilities', ['id' => 4]);

        // Deleted eligibility.
        $this->json('PUT',
            '/eligibilities/1',
            [
                'start_date' => '2019-01-01',
                'end_date' => '2019-12-01',
                'notes' => 'Modified eligibility notes',
                'is_eligible' => true,
            ]
        )
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ])
            ->seeStatusCode(404)
            ->notSeeInDatabase('eligibilities', ['id' => 999])
            ->notSeeInDatabase('eligibilities', ['id' => 4]);

        // Unallowed additional property.
        $this->json('PUT',
            '/eligibilities/2',
            [
                'start_date' => '2019-01-01',
                'end_date' => '2019-12-01',
                'notes' => 'Modified eligibility notes', // --> modified
                'is_eligible' => true,
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
            ->seeInDatabase('eligibilities', ['id' => 2])
            ->notSeeInDatabase('eligibilities', ['id' => 2, 'notes' => 'Modified eligibility notes']);

        // Missing required start_date.
        $this->json('PUT',
            '/eligibilities/2',
            [
                'end_date' => '2019-12-01',
                'notes' => 'Modified eligibility notes', // --> modified
                'is_eligible' => true,
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
            ->seeInDatabase('eligibilities', ['id' => 2])
            ->notSeeInDatabase('eligibilities', ['id' => 2, 'notes' => 'Modified eligibility notes']);

        // Missing required end_date.
        $this->json('PUT',
            '/eligibilities/2',
            [
                'start_date' => '2019-01-01',
                'notes' => 'Modified eligibility notes', // --> modified
                'is_eligible' => true,
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
            ->seeInDatabase('eligibilities', ['id' => 2])
            ->notSeeInDatabase('eligibilities', ['id' => 2, 'notes' => 'Modified eligibility notes']);

        // Missing required is_eligible.
        $this->json('PUT',
            '/eligibilities/2',
            [
                'start_date' => '2019-01-01',
                'end_date' => '2019-12-01',
                'notes' => 'Modified eligibility notes', // --> modified
            ]
        )
            ->seeJsonEquals([
                'status_code' => 400,
                'status' => 'Bad Request',
                'message' => 'Request is not valid',
                'data' => [
                    'is_eligible' => [
                        'code error_required',
                        'in body',
                    ]
                ]
            ])
            ->seeInDatabase('eligibilities', ['id' => 2])
            ->notSeeInDatabase('eligibilities', ['id' => 2, 'notes' => 'Modified eligibility notes']);

        // Identical dates.
        $this->json('PUT',
            '/eligibilities/2',
            [
                'start_date' => '2019-01-01',
                'end_date' => '2019-01-01',
                'notes' => 'Modified eligibility notes', // --> modified
                'is_eligible' => true,
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
            ->seeInDatabase('eligibilities', ['id' => 2])
            ->notSeeInDatabase('eligibilities', ['id' => 2, 'notes' => 'Modified eligibility notes']);

        // Switched dates.
        $this->json('PUT',
            '/eligibilities/2',
            [
                'start_date' => '2019-12-01',
                'end_date' => '2019-01-01',
                'notes' => 'Modified eligibility notes', // --> modified
                'is_eligible' => true,
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
            ->seeInDatabase('eligibilities', ['id' => 2])
            ->notSeeInDatabase('eligibilities', ['id' => 2, 'notes' => 'Modified eligibility notes']);

        // @todo add test related to overlapping time ranges
        // @todo add further tests related to invalid attribute format
        // @todo if eligibilities are enforced (config parameter), check with the ones that make internships allowed

    }

    /**
     * Delete an eligibility: success.
     */
    public function testDeleteById()
    {

        // Existing eligibility
        $this->json('DELETE', '/eligibilities/2')
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource deleted',
            ])
            ->seeStatusCode(200)
            ->notSeeInDatabase('eligibilities', ['id' => 2]);

    }

    /**
     * Delete an eligibility: failure.
     */
    public function testDeleteByIdFailure()
    {

        // Non existing eligibility
        $this->json('DELETE', '/eligibilities/999')
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ])
            ->seeStatusCode(404)
            ->notSeeInDatabase('eligibilities', ['id' => 999]);

        // Already deleted eligibility
        $this->json('DELETE', '/eligibilities/1')
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ])
            ->seeStatusCode(404);

        // Invalid ID
        $this->json('DELETE', '/eligibilities/abc')
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
            ->notSeeInDatabase('eligibilities', ['id' => 'abc']);

        // @todo if eligibilities are enforced (config parameter), check with the ones that make internships allowed

    }

}

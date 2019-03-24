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
            ->seeInDatabase('eligibilities', ['id' => 4, 'student_id' => 1, 'deleted_at' => null])
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
            ->seeInDatabase('eligibilities', ['id' => 5, 'student_id' => 2, 'deleted_at' => null])
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

        // @todo add further tests related to invalid attribute format
        // @todo add further tests related overlapping dates

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

    }

}
<?php
declare(strict_types = 1);

class EvaluationsTest extends TestCase
{

    /**
     * Get all evaluations.
     */
    public function testGet()
    {
        $this->json('GET', '/evaluations')
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource(s) found',
                'data' => [
                    array_merge(
                        [
                            'id' => 1,
                            'internship' => [
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
                            'clinical_tutor_id' => 456,
                            'notes' => 'First evaluation notes',
                            'created_at' => '2019-01-25 02:00:00',
                            'updated_at' => '2019-01-25 02:00:00',
                        ],
                        EvaluationsTableSeeder::generateItemValues($this->app['config']['internships']['evaluations']['items'])
                    ),
                ],
            ])
            ->seeStatusCode(200);
    }

    /**
     * Get evaluation by ID.
     */
    public function testGetById()
    {

        // Existing
        $this->json('GET', '/evaluations/1')
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource successfully retrieved/created/modified',
                'data' => array_merge(
                    [
                        'id' => 1,
                        'internship' => [
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
                        'clinical_tutor_id' => 456,
                        'notes' => 'First evaluation notes',
                        'created_at' => '2019-01-25 02:00:00',
                        'updated_at' => '2019-01-25 02:00:00',
                    ],
                    EvaluationsTableSeeder::generateItemValues($this->app['config']['internships']['evaluations']['items'])
                ),
            ])
            ->seeStatusCode(200);

    }

    /**
     * Get evaluation by ID: failure.
     */
    public function testGetByIdFailure()
    {

        // Deleted
        $this->json('GET', '/evaluations/2')
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ])
            ->seeStatusCode(404);

        // Non existing
        $this->json('GET', '/evaluations/9999')
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ])
            ->seeStatusCode(404);

        // Invalid ID
        $this->json('GET', '/evaluations/abc')
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
     * Get internship's evaluation: success.
     */
    public function testGetRelatedToInternship()
    {

        // Existing
        $this->json('GET', '/internships/1/evaluation')
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource successfully retrieved/created/modified',
                'data' => array_merge(
                    [
                        'id' => 1,
                        'clinical_tutor_id' => 456,
                        'notes' => 'First evaluation notes',
                        'created_at' => '2019-01-25 02:00:00',
                        'updated_at' => '2019-01-25 02:00:00',
                    ],
                    EvaluationsTableSeeder::generateItemValues($this->app['config']['internships']['evaluations']['items'])
                ),
            ])
            ->seeStatusCode(200);

    }

    /**
     * Get internship's evaluation: failure.
     */
    public function testGetRelatedToInternshipFailure()
    {

        // Non existing internship evaluation
        $this->json('GET', '/internships/2/evaluation')
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ])
            ->seeStatusCode(404);

        // Non existing internship
        $this->json('GET', '/internships/999/evaluation')
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ])
            ->seeStatusCode(404);

        // Invalid internship ID
        $this->json('GET', '/internships/abc/evaluation')
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
     * Create an internship's evaluation: success.
     */
    public function testCreateRelatedToInternship()
    {

        // Existing internship
        $this->json('POST',
            '/internships/3/evaluation',
            array_merge(
                [
                    'clinical_tutor_id' => 123,
                    'notes' => 'Another evaluation notes',
                ],
                EvaluationsTableSeeder::generateItemValues($this->app['config']['internships']['evaluations']['items'])
            )
        )
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource successfully retrieved/created/modified',
                'data' => array_merge(
                    [
                        'id' => 3,
                        'clinical_tutor_id' => 123,
                        'notes' => 'Another evaluation notes',
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ],
                    EvaluationsTableSeeder::generateItemValues($this->app['config']['internships']['evaluations']['items'])
                )
            ])
            ->seeStatusCode(200)
            ->seeInDatabase('evaluations', ['id' => 3, 'internship_id' => 3, 'clinical_tutor_id' => 123, 'notes' => 'Another evaluation notes', 'deleted_at' => null])
            ->notSeeInDatabase('evaluations', ['id' => 4]);

        // Existing internship, no notes
        $this->json('POST',
            '/internships/2/evaluation',
            array_merge(
                [
                    'clinical_tutor_id' => 123,
                ],
                EvaluationsTableSeeder::generateItemValues($this->app['config']['internships']['evaluations']['items'])
            )
        )
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource successfully retrieved/created/modified',
                'data' => array_merge(
                    [
                        'id' => 4,
                        'clinical_tutor_id' => 123,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ],
                    EvaluationsTableSeeder::generateItemValues($this->app['config']['internships']['evaluations']['items'])
                ),
            ])
            ->seeStatusCode(200)
            ->seeInDatabase('evaluations', ['id' => 4, 'internship_id' => 2, 'clinical_tutor_id' => 123, 'notes' => null, 'deleted_at' => null])
            ->notSeeInDatabase('evaluations', ['id' => 5]);

        // @todo add tests with items

    }

    /**
     * Create an internship's evaluation: failure.
     */
    public function testCreateRelatedToInternshipFailure()
    {

        // Non existing internship
        $this->json('POST',
            '/internships/999/evaluation',
            array_merge(
                [
                    'clinical_tutor_id' => 123,
                    'notes' => 'Another evaluation notes',
                ],
                EvaluationsTableSeeder::generateItemValues($this->app['config']['internships']['evaluations']['items'])
            )
        )
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ])
            ->seeStatusCode(404)
            ->notSeeInDatabase('evaluations', ['id' => 3])
            ->notSeeInDatabase('evaluations', ['internship_id' => 999]);

        // Invalid internship ID
        $this->json('POST',
            '/internships/abc/evaluation',
            array_merge(
                [
                    'clinical_tutor_id' => 123,
                    'notes' => 'Another evaluation notes',
                ],
                EvaluationsTableSeeder::generateItemValues($this->app['config']['internships']['evaluations']['items'])
            )
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
            ->notSeeInDatabase('evaluations', ['id' => 3])
            ->notSeeInDatabase('evaluations', ['internship_id' => 'abc']);

        // Internship already with evaluation
        $this->json('POST',
            '/internships/1/evaluation',
            array_merge(
                [
                    'clinical_tutor_id' => 123,
                    'notes' => 'Another evaluation notes',
                ],
                EvaluationsTableSeeder::generateItemValues($this->app['config']['internships']['evaluations']['items'])
            )
        )
            ->seeJsonEquals([
                'status_code' => 400,
                'status' => 'Bad Request',
                'message' => 'Request is not valid',
                'data' => [
                    'internship_id' => [
                        'Internship already has evaluation',
                    ],
                ]
            ])
            ->seeStatusCode(400)
            ->notSeeInDatabase('evaluations', ['id' => 3]);

        // Internship with start date in the future
        $this->json('POST',
            '/internships/4/evaluation',
            array_merge(
                [
                    'clinical_tutor_id' => 123,
                    'notes' => 'Another evaluation notes',
                ],
                EvaluationsTableSeeder::generateItemValues($this->app['config']['internships']['evaluations']['items'])
            )
        )
            ->seeJsonEquals([
                'status_code' => 400,
                'status' => 'Bad Request',
                'message' => 'Request is not valid',
                'data' => [
                    'internship_id' => [
                        'Internship not started yet',
                    ],
                ]
            ])
            ->seeStatusCode(400)
            ->notSeeInDatabase('evaluations', ['id' => 3]);

        // Missing required clinical_tutor_id
        $this->json('POST',
            '/internships/3/evaluation',
            array_merge(
                [
                    'notes' => 'Another evaluation notes',
                ],
                EvaluationsTableSeeder::generateItemValues($this->app['config']['internships']['evaluations']['items'])
            )
        )
            ->seeJsonEquals([
                'status_code' => 400,
                'status' => 'Bad Request',
                'message' => 'Request is not valid',
                'data' => [
                    'clinical_tutor_id' => [
                        'code error_required',
                        'in body',
                    ]
                ]
            ])
            ->seeStatusCode(400)
            ->notSeeInDatabase('evaluations', ['id' => 3]);

        // @todo add further tests related to invalid attribute format

    }

    /**
     * Modify an internship evaluation: success.
     */
    public function testModifyById()
    {

        $this->json('PUT',
            '/evaluations/1',
            array_merge(
                [
                    'clinical_tutor_id' => 456,
                    'notes' => 'First evaluation notes modified', // --> modified
                ],
                EvaluationsTableSeeder::generateItemValues($this->app['config']['internships']['evaluations']['items'])
            )
        )
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource successfully retrieved/created/modified',
                'data' => array_merge(
                    [
                        'id' => 1,
                        'internship' => [
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
                        'clinical_tutor_id' => 456,
                        'notes' => 'First evaluation notes modified',
                        'created_at' => '2019-01-25 02:00:00',
                        'updated_at' => date('Y-m-d H:i:s'),
                    ],
                    EvaluationsTableSeeder::generateItemValues($this->app['config']['internships']['evaluations']['items'])
                ),
            ])
            ->seeStatusCode(200)
            ->seeInDatabase('evaluations', ['id' => 1, 'notes' => 'First evaluation notes modified'])
            ->notSeeInDatabase('evaluations', ['id' => 1, 'notes' => 'First evaluation notes'])
            ->notSeeInDatabase('evaluations', ['id' => 3]);

        // Remove notes
        $this->json('PUT',
            '/evaluations/1',
            array_merge(
                [
                    'clinical_tutor_id' => 456,
                ],
                EvaluationsTableSeeder::generateItemValues($this->app['config']['internships']['evaluations']['items'])
            )
        )
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource successfully retrieved/created/modified',
                'data' => array_merge(
                    [
                        'id' => 1,
                        'internship' => [
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
                        'clinical_tutor_id' => 456,
                        'created_at' => '2019-01-25 02:00:00',
                        'updated_at' => date('Y-m-d H:i:s'),
                    ],
                    EvaluationsTableSeeder::generateItemValues($this->app['config']['internships']['evaluations']['items'])
                ),
            ])
            ->seeStatusCode(200)
            ->seeInDatabase('evaluations', ['id' => 1, 'notes' => null])
            ->notSeeInDatabase('evaluations', ['id' => 1, 'notes' => 'First evaluation notes modified'])
            ->notSeeInDatabase('evaluations', ['id' => 3]);

    }

    /**
     * Modify an internship evaluation: failure.
     */
    public function testModifyByIdFailure()
    {

        // Non existing ID
        $this->json('PUT',
            '/evaluations/999',
            array_merge(
                [
                    'clinical_tutor_id' => 789,
                    'notes' => 'Second evaluation notes modified', // --> modified
                ],
                EvaluationsTableSeeder::generateItemValues($this->app['config']['internships']['evaluations']['items'])
            )
        )
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ])
            ->seeStatusCode(404)
            ->notSeeInDatabase('evaluations', ['id' => 999])
            ->notSeeInDatabase('evaluations', ['id' => 3]);

        // Invalid ID
        $this->json('PUT',
            '/evaluations/abc',
            array_merge(
                [
                    'clinical_tutor_id' => 789,
                    'notes' => 'Second evaluation notes modified', // --> modified
                ],
                EvaluationsTableSeeder::generateItemValues($this->app['config']['internships']['evaluations']['items'])
            )
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
            ->notSeeInDatabase('evaluations', ['id' => 'abc'])
            ->notSeeInDatabase('evaluations', ['id' => 3]);

        // Different clinical tutor
        $this->json('PUT',
            '/evaluations/1',
            array_merge(
                [
                    'clinical_tutor_id' => 123,  // --> modified
                    'notes' => 'First evaluation notes',
                ],
                EvaluationsTableSeeder::generateItemValues($this->app['config']['internships']['evaluations']['items'])
            )
        )
            ->seeJsonEquals([
                'status_code' => 400,
                'status' => 'Bad Request',
                'message' => 'Request is not valid',
                'data' => [
                    'clinical_tutor_id' => [
                        'The clinical tutor id cannot be changed',
                    ],
                ]
            ])
            ->seeStatusCode(400)
            ->seeInDatabase('evaluations', ['id' => 1, 'clinical_tutor_id' => 456])
            ->notSeeInDatabase('evaluations', ['id' => 1, 'clinical_tutor_id' => 123]);

        // Missing required clinical_tutor_id
        $this->json('PUT',
            '/evaluations/1',
            array_merge(
                [
                    'notes' => 'Another evaluation notes', // --> modified
                ],
                EvaluationsTableSeeder::generateItemValues($this->app['config']['internships']['evaluations']['items'])
            )
        )
            ->seeJsonEquals([
                'status_code' => 400,
                'status' => 'Bad Request',
                'message' => 'Request is not valid',
                'data' => [
                    'clinical_tutor_id' => [
                        'code error_required',
                        'in body',
                    ]
                ]
            ])
            ->seeStatusCode(400)
            ->seeInDatabase('evaluations', ['id' => 1, 'notes' => 'First evaluation notes'])
            ->notSeeInDatabase('evaluations', ['id' => 1, 'notes' => 'Another evaluation notes']);

        // @todo add further tests related to invalid attribute format
        // @todo add tests related to items

    }

    /**
     * Delete an internship evaluation: success.
     */
    public function testDeleteById()
    {

        // Existing internship
        $this->json('DELETE', '/evaluations/1')
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource deleted',
            ])
            ->seeStatusCode(200)
            ->seeInDatabase('evaluations', ['id' => 1, 'deleted_at' => date('Y-m-d H:i:s')])
            ->notSeeInDatabase('evaluations', ['id' => 1, 'deleted_at' => null]);

    }

    /**
     * Delete an internship evaluation: failure.
     */
    public function testDeleteByIdFailure()
    {

        // Non existing evaluation
        $this->json('DELETE', '/evaluations/999')
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ])
            ->seeStatusCode(404)
            ->notSeeInDatabase('evaluations', ['id' => 999]);

        // Invalid ID
        $this->json('DELETE', '/evaluations/abc')
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
            ->notSeeInDatabase('evaluations', ['id' => 'abc']);

    }

}
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
                    [
                        'id' => 1,
                        'stage' => [
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
                        'item_1_1' => 'A',
                        'item_1_2' => 'B',
                        'item_1_4' => 'D',
                        'item_2_2' => 'NV',
                        'item_2_3' => 'A',
                        'item_2_4' => 'B',
                        'item_3_1' => 'D',
                        'item_3_2' => 'E',
                        'item_3_3' => 'NV',
                        'item_3_4' => 'A',
                        'item_4_1' => 'B',
                        'item_4_2' => 'C',
                        'item_4_3' => 'D',
                        'item_4_5' => 'NV',
                        'item_5_1' => 'A',
                        'item_5_2' => 'B',
                        'item_5_3' => 'C',
                        'item_5_4' => 'D',
                        'item_5_5' => 'E',
                        'item_6_1' => 'NV',
                        'item_6_2' => 'A',
                        'item_6_3' => 'B',
                        'item_7_1' => 'C',
                        'item_7_2' => 'D',
                        'item_8_1' => 'E',
                        'item_8_2' => 'NV',
                        'item_8_3' => 'A',
                        'item_8_4' => 'B',
                        'item_9_1' => 'D',
                        'item_9_2' => 'E',
                        'item_10_1' => 'NV',
                        'item_10_2' => 'A',
                        'item_11_1' => 'B',
                        'item_11_2' => 'C',
                        'item_12_1' => 'D',
                        'item_12_2' => 'E',
                        'item_12_3' => 'NV',
                        'item_13_1' => 'A',
                        'item_13_2' => 'B'
                    ],
                ]
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
                'data' => [
                    'id' => 1,
                    'stage' => [
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
                    'item_1_1' => 'A',
                    'item_1_2' => 'B',
                    'item_1_4' => 'D',
                    'item_2_2' => 'NV',
                    'item_2_3' => 'A',
                    'item_2_4' => 'B',
                    'item_3_1' => 'D',
                    'item_3_2' => 'E',
                    'item_3_3' => 'NV',
                    'item_3_4' => 'A',
                    'item_4_1' => 'B',
                    'item_4_2' => 'C',
                    'item_4_3' => 'D',
                    'item_4_5' => 'NV',
                    'item_5_1' => 'A',
                    'item_5_2' => 'B',
                    'item_5_3' => 'C',
                    'item_5_4' => 'D',
                    'item_5_5' => 'E',
                    'item_6_1' => 'NV',
                    'item_6_2' => 'A',
                    'item_6_3' => 'B',
                    'item_7_1' => 'C',
                    'item_7_2' => 'D',
                    'item_8_1' => 'E',
                    'item_8_2' => 'NV',
                    'item_8_3' => 'A',
                    'item_8_4' => 'B',
                    'item_9_1' => 'D',
                    'item_9_2' => 'E',
                    'item_10_1' => 'NV',
                    'item_10_2' => 'A',
                    'item_11_1' => 'B',
                    'item_11_2' => 'C',
                    'item_12_1' => 'D',
                    'item_12_2' => 'E',
                    'item_12_3' => 'NV',
                    'item_13_1' => 'A',
                    'item_13_2' => 'B'
                ],
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
     * Get stage's evaluation: success.
     */
    public function testGetRelatedToStage()
    {

        // Existing
        $this->json('GET', '/stages/1/evaluation')
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource successfully retrieved/created/modified',
                'data' => [
                    'id' => 1,
                    'clinical_tutor_id' => 456,
                    'notes' => 'First evaluation notes',
                    'created_at' => '2019-01-25 02:00:00',
                    'updated_at' => '2019-01-25 02:00:00',
                    'item_1_1' => 'A',
                    'item_1_2' => 'B',
                    'item_1_4' => 'D',
                    'item_2_2' => 'NV',
                    'item_2_3' => 'A',
                    'item_2_4' => 'B',
                    'item_3_1' => 'D',
                    'item_3_2' => 'E',
                    'item_3_3' => 'NV',
                    'item_3_4' => 'A',
                    'item_4_1' => 'B',
                    'item_4_2' => 'C',
                    'item_4_3' => 'D',
                    'item_4_5' => 'NV',
                    'item_5_1' => 'A',
                    'item_5_2' => 'B',
                    'item_5_3' => 'C',
                    'item_5_4' => 'D',
                    'item_5_5' => 'E',
                    'item_6_1' => 'NV',
                    'item_6_2' => 'A',
                    'item_6_3' => 'B',
                    'item_7_1' => 'C',
                    'item_7_2' => 'D',
                    'item_8_1' => 'E',
                    'item_8_2' => 'NV',
                    'item_8_3' => 'A',
                    'item_8_4' => 'B',
                    'item_9_1' => 'D',
                    'item_9_2' => 'E',
                    'item_10_1' => 'NV',
                    'item_10_2' => 'A',
                    'item_11_1' => 'B',
                    'item_11_2' => 'C',
                    'item_12_1' => 'D',
                    'item_12_2' => 'E',
                    'item_12_3' => 'NV',
                    'item_13_1' => 'A',
                    'item_13_2' => 'B'
                ],
            ])
            ->seeStatusCode(200);

    }

    /**
     * Get stage's evaluation: failure.
     */
    public function testGetRelatedToStageFailure()
    {

        // Non existing stage evaluation
        $this->json('GET', '/stages/2/evaluation')
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ])
            ->seeStatusCode(404);

        // Non existing stage
        $this->json('GET', '/stages/999/evaluation')
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ])
            ->seeStatusCode(404);

        // Invalid stage ID
        $this->json('GET', '/stages/abc/evaluation')
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
     * Create a stage's evaluation: success.
     */
    public function testCreateRelatedToStage()
    {

        // Existing stage
        $this->json('POST',
            '/stages/3/evaluation',
            [
                'clinical_tutor_id' => 123,
                'notes' => 'Another evaluation notes',
            ]
        )
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource successfully retrieved/created/modified',
                'data' => [
                    'id' => 3,
                    'clinical_tutor_id' => 123,
                    'notes' => 'Another evaluation notes',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
            ])
            ->seeStatusCode(200)
            ->seeInDatabase('evaluations', ['id' => 3, 'stage_id' => 3, 'clinical_tutor_id' => 123, 'notes' => 'Another evaluation notes', 'deleted_at' => null])
            ->notSeeInDatabase('evaluations', ['id' => 4]);

        // Existing stage, no notes
        $this->json('POST',
            '/stages/2/evaluation',
            [
                'clinical_tutor_id' => 123,
            ]
        )
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource successfully retrieved/created/modified',
                'data' => [
                    'id' => 4,
                    'clinical_tutor_id' => 123,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
            ])
            ->seeStatusCode(200)
            ->seeInDatabase('evaluations', ['id' => 4, 'stage_id' => 2, 'clinical_tutor_id' => 123, 'notes' => null, 'deleted_at' => null])
            ->notSeeInDatabase('evaluations', ['id' => 5]);

        // @todo add tests with items

    }

    /**
     * Create a stage's evaluation: failure.
     */
    public function testCreateRelatedToStageFailure()
    {

        // Non existing stage
        $this->json('POST',
            '/stages/999/evaluation',
            [
                'clinical_tutor_id' => 123,
                'notes' => 'Another evaluation notes',
            ]
        )
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ])
            ->seeStatusCode(404)
            ->notSeeInDatabase('evaluations', ['id' => 3])
            ->notSeeInDatabase('evaluations', ['stage_id' => 999]);

        // Invalid stage ID
        $this->json('POST',
            '/stages/abc/evaluation',
            [
                'clinical_tutor_id' => 123,
                'notes' => 'Another evaluation notes',
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
            ->notSeeInDatabase('evaluations', ['id' => 3])
            ->notSeeInDatabase('evaluations', ['stage_id' => 'abc']);

        // Stage already with evaluation
        $this->json('POST',
            '/stages/1/evaluation',
            [
                'clinical_tutor_id' => 123,
                'notes' => 'Another evaluation notes',
            ]
        )
            ->seeJsonEquals([
                'status_code' => 400,
                'status' => 'Bad Request',
                'message' => 'Request is not valid',
                'data' => [
                    'stage_id' => [
                        'Stage already has evaluation',
                    ],
                ]
            ])
            ->seeStatusCode(400)
            ->notSeeInDatabase('evaluations', ['id' => 3]);

        // Stage with start date in the future
        $this->json('POST',
            '/stages/4/evaluation',
            [
                'clinical_tutor_id' => 123,
                'notes' => 'Another evaluation notes',
            ]
        )
            ->seeJsonEquals([
                'status_code' => 400,
                'status' => 'Bad Request',
                'message' => 'Request is not valid',
                'data' => [
                    'stage_id' => [
                        'Stage not started yet',
                    ],
                ]
            ])
            ->seeStatusCode(400)
            ->notSeeInDatabase('evaluations', ['id' => 3]);

        // Missing required clinical_tutor_id
        $this->json('POST',
            '/stages/3/evaluation',
            [
                'notes' => 'Another evaluation notes',
            ]
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
        // @todo add tests with items

    }

    /**
     * Modify a stage evaluation: success.
     */
    public function testModifyById()
    {

        $this->json('PUT',
            '/evaluations/1',
            [
                'clinical_tutor_id' => 456,
                'notes' => 'First evaluation notes modified', // --> modified
            ]
        )
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource successfully retrieved/created/modified',
                'data' => [
                    'id' => 1,
                    'stage' => [
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
                    'item_1_1' => 'A',
                    'item_1_2' => 'B',
                    'item_1_4' => 'D',
                    'item_2_2' => 'NV',
                    'item_2_3' => 'A',
                    'item_2_4' => 'B',
                    'item_3_1' => 'D',
                    'item_3_2' => 'E',
                    'item_3_3' => 'NV',
                    'item_3_4' => 'A',
                    'item_4_1' => 'B',
                    'item_4_2' => 'C',
                    'item_4_3' => 'D',
                    'item_4_5' => 'NV',
                    'item_5_1' => 'A',
                    'item_5_2' => 'B',
                    'item_5_3' => 'C',
                    'item_5_4' => 'D',
                    'item_5_5' => 'E',
                    'item_6_1' => 'NV',
                    'item_6_2' => 'A',
                    'item_6_3' => 'B',
                    'item_7_1' => 'C',
                    'item_7_2' => 'D',
                    'item_8_1' => 'E',
                    'item_8_2' => 'NV',
                    'item_8_3' => 'A',
                    'item_8_4' => 'B',
                    'item_9_1' => 'D',
                    'item_9_2' => 'E',
                    'item_10_1' => 'NV',
                    'item_10_2' => 'A',
                    'item_11_1' => 'B',
                    'item_11_2' => 'C',
                    'item_12_1' => 'D',
                    'item_12_2' => 'E',
                    'item_12_3' => 'NV',
                    'item_13_1' => 'A',
                    'item_13_2' => 'B'
                ],
            ])
            ->seeStatusCode(200)
            ->seeInDatabase('evaluations', ['id' => 1, 'notes' => 'First evaluation notes modified'])
            ->notSeeInDatabase('evaluations', ['id' => 1, 'notes' => 'First evaluation notes'])
            ->notSeeInDatabase('evaluations', ['id' => 3]);

        // Remove notes
        $this->json('PUT',
            '/evaluations/1',
            [
                'clinical_tutor_id' => 456,
            ]
        )
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource successfully retrieved/created/modified',
                'data' => [
                    'id' => 1,
                    'stage' => [
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
                    'item_1_1' => 'A',
                    'item_1_2' => 'B',
                    'item_1_4' => 'D',
                    'item_2_2' => 'NV',
                    'item_2_3' => 'A',
                    'item_2_4' => 'B',
                    'item_3_1' => 'D',
                    'item_3_2' => 'E',
                    'item_3_3' => 'NV',
                    'item_3_4' => 'A',
                    'item_4_1' => 'B',
                    'item_4_2' => 'C',
                    'item_4_3' => 'D',
                    'item_4_5' => 'NV',
                    'item_5_1' => 'A',
                    'item_5_2' => 'B',
                    'item_5_3' => 'C',
                    'item_5_4' => 'D',
                    'item_5_5' => 'E',
                    'item_6_1' => 'NV',
                    'item_6_2' => 'A',
                    'item_6_3' => 'B',
                    'item_7_1' => 'C',
                    'item_7_2' => 'D',
                    'item_8_1' => 'E',
                    'item_8_2' => 'NV',
                    'item_8_3' => 'A',
                    'item_8_4' => 'B',
                    'item_9_1' => 'D',
                    'item_9_2' => 'E',
                    'item_10_1' => 'NV',
                    'item_10_2' => 'A',
                    'item_11_1' => 'B',
                    'item_11_2' => 'C',
                    'item_12_1' => 'D',
                    'item_12_2' => 'E',
                    'item_12_3' => 'NV',
                    'item_13_1' => 'A',
                    'item_13_2' => 'B'
                ],
            ])
            ->seeStatusCode(200)
            ->seeInDatabase('evaluations', ['id' => 1, 'notes' => null])
            ->notSeeInDatabase('evaluations', ['id' => 1, 'notes' => 'First evaluation notes modified'])
            ->notSeeInDatabase('evaluations', ['id' => 3]);

    }

    /**
     * Modify a stage evaluation: failure.
     */
    public function testModifyByIdFailure()
    {

        // Non existing ID
        $this->json('PUT',
            '/evaluations/999',
            [
                'clinical_tutor_id' => 789,
                'notes' => 'Second evaluation notes modified', // --> modified
            ]
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
            [
                'clinical_tutor_id' => 789,
                'notes' => 'Second evaluation notes modified', // --> modified
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
            ->notSeeInDatabase('evaluations', ['id' => 'abc'])
            ->notSeeInDatabase('evaluations', ['id' => 3]);

        // Different clinical tutor
        $this->json('PUT',
            '/evaluations/1',
            [
                'clinical_tutor_id' => 123,  // --> modified
                'notes' => 'First evaluation notes',
            ]
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
            [
                'notes' => 'Another evaluation notes', // --> modified
            ]
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

    }

    /**
     * Delete a stage evaluation: success.
     */
    public function testDeleteById()
    {

        // Existing stage
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
     * Delete a stage evaluation: failure.
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
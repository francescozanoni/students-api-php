<?php
declare(strict_types = 1);

class InterruptionReportsTest extends TestCase
{

    /**
     * Get all interruption reports.
     */
    public function testGet()
    {
        $this->json('GET', '/interruption_reports')
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource(s) found',
                'data' => [
                    [
                        'id' => 2,
                        'stage' => [
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
                        'clinical_tutor_id' => 789,
                        'notes' => 'Second interruption report notes',
                        'created_at' => '2019-01-31 02:00:00',
                        'updated_at' => '2019-01-31 02:00:00',
                    ],
                ]
            ])
            ->seeStatusCode(200);
    }

    /**
     * Get interruption report by ID.
     */
    public function testGetById()
    {

        // Existing
        $this->json('GET', '/interruption_reports/2')
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource successfully retrieved/created/modified',
                'data' => [
                    'id' => 2,
                    'stage' => [
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
                        'is_interrupted' => true,
                    ],
                    'clinical_tutor_id' => 789,
                    'notes' => 'Second interruption report notes',
                    'created_at' => '2019-01-31 02:00:00',
                    'updated_at' => '2019-01-31 02:00:00',
                ],
            ])
            ->seeStatusCode(200);

    }

    /**
     * Get interruption report by ID: failure.
     */
    public function testGetByIdFailure()
    {

        // Deleted
        $this->json('GET', '/interruption_reports/1')
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ])
            ->seeStatusCode(404);

        // Non existing
        $this->json('GET', '/interruption_reports/9999')
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ])
            ->seeStatusCode(404);

        // Invalid ID
        $this->json('GET', '/interruption_reports/abc')
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
     * Get stage's interruption report: success.
     */
    public function testGetRelatedToStage()
    {

        // Existing
        $this->json('GET', '/stages/2/interruption_report')
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource successfully retrieved/created/modified',
                'data' => [
                    'id' => 2,
                    'clinical_tutor_id' => 789,
                    'notes' => 'Second interruption report notes',
                    'created_at' => '2019-01-31 02:00:00',
                    'updated_at' => '2019-01-31 02:00:00',
                ],
            ])
            ->seeStatusCode(200);

    }

    /**
     * Get stage's interruption report: failure.
     */
    public function testGetRelatedToStageFailure()
    {

        // Non existing stage interruption report
        $this->json('GET', '/stages/1/interruption_report')
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ])
            ->seeStatusCode(404);

        // Non existing stage
        $this->json('GET', '/stages/999/interruption_report')
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ])
            ->seeStatusCode(404);

        // Invalid stage ID
        $this->json('GET', '/stages/abc/interruption_report')
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
     * Create a stage's interruption report: success.
     */
    public function testCreateRelatedToStage()
    {

        // Existing stage
        $this->json('POST',
            '/stages/3/interruption_report',
            [
                'clinical_tutor_id' => 123,
                'notes' => 'Another interruption report notes',
            ]
        )
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource successfully retrieved/created/modified',
                'data' => [
                    'id' => 3,
                    'clinical_tutor_id' => 123,
                    'notes' => 'Another interruption report notes',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
            ])
            ->seeStatusCode(200)
            ->seeInDatabase('interruption_reports', ['id' => 3, 'stage_id' => 3, 'clinical_tutor_id' => 123, 'deleted_at' => null])
            ->notSeeInDatabase('interruption_reports', ['id' => 4]);

    }

    /**
     * Create a stage's interruption report: failure.
     */
    public function testCreateRelatedToStageFailure()
    {

        // Non existing stage
        $this->json('POST',
            '/stages/999/interruption_report',
            [
                'clinical_tutor_id' => 123,
                'notes' => 'Another interruption report notes',
            ]
        )
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ])
            ->seeStatusCode(404)
            ->notSeeInDatabase('interruption_reports', ['id' => 3])
            ->notSeeInDatabase('interruption_reports', ['stage_id' => 999]);

        // Invalid stage ID
        $this->json('POST',
            '/stages/abc/interruption_report',
            [
                'clinical_tutor_id' => 123,
                'notes' => 'Another interruption report notes',
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
            ->notSeeInDatabase('interruption_reports', ['id' => 3])
            ->notSeeInDatabase('interruption_reports', ['stage_id' => 'abc']);

        // Non interrupted stage
        $this->json('POST',
            '/stages/1/interruption_report',
            [
                'clinical_tutor_id' => 123,
                'notes' => 'Another interruption report notes',
            ]
        )
            ->seeJsonEquals([
                'status_code' => 400,
                'status' => 'Bad Request',
                'message' => 'Request is not valid',
                'data' => [
                    'stage_id' => [
                        'Stage is not interrupted',
                    ],
                ]
            ])
            ->seeStatusCode(400)
            ->notSeeInDatabase('interruption_reports', ['id' => 3])
            ->notSeeInDatabase('interruption_reports', ['stage_id' => 1]);

        // Stage already with interruption report
        $this->json('POST',
            '/stages/2/interruption_report',
            [
                'clinical_tutor_id' => 123,
                'notes' => 'Another interruption report notes',
            ]
        )
            ->seeJsonEquals([
                'status_code' => 400,
                'status' => 'Bad Request',
                'message' => 'Request is not valid',
                'data' => [
                    'stage_id' => [
                        'Stage already has interruption report',
                    ],
                ]
            ])
            ->seeStatusCode(400)
            ->notSeeInDatabase('interruption_reports', ['id' => 3]);

        // Stage with start date in the future
        $this->json('POST',
            '/stages/4/interruption_report',
            [
                'clinical_tutor_id' => 123,
                'notes' => 'Another interruption report notes',
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
            ->notSeeInDatabase('interruption_reports', ['id' => 3]);
            
        // Missing required clinical_tutor_id
        $this->json('POST',
            '/stages/3/interruption_report',
            [
                'notes' => 'Another interruption report notes',
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
            ->notSeeInDatabase('interruption_reports', ['id' => 3]);
            
        // Missing required notes
        $this->json('POST',
            '/stages/3/interruption_report',
            [
                'clinical_tutor_id' => 123,
            ]
        )
            ->seeJsonEquals([
                'status_code' => 400,
                'status' => 'Bad Request',
                'message' => 'Request is not valid',
                'data' => [
                    'notes' => [
                        'code error_required',
                        'in body',
                    ]
                ]
            ])
            ->seeStatusCode(400)
            ->notSeeInDatabase('interruption_reports', ['id' => 3]);
            
        // @todo add further tests related to invalid attribute format

    }
    
    /**
     * Modify a stage interruption report: success.
     */
    public function testModifyById()
    {

        $this->json('PUT',
            '/interruption_reports/2',
            [
                'clinical_tutor_id' => 789,
                'notes' => 'Second interruption report notes modified', // --> modified
            ]
        )
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource successfully retrieved/created/modified',
                'data' => [
                        'id' => 2,
                        'stage' => [
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
                        'clinical_tutor_id' => 789,
                        'notes' => 'Second interruption report notes modified',
                        'created_at' => '2019-01-31 02:00:00',
                        'updated_at' => date('Y-m-d H:i:s'),
                ],
            ])
            ->seeStatusCode(200)
            ->seeInDatabase('interruption_reports', ['id' => 2, 'notes' => 'Second interruption report notes modified'])
            ->notSeeInDatabase('interruption_reports', ['id' => 2, 'notes' => 'Second interruption report notes'])
            ->notSeeInDatabase('interruption_reports', ['id' => 3]);

    }

    /**
     * Modify a stage interruption report: failure.
     */
    public function testModifyByIdFailure()
    {

        // Non existing ID
        $this->json('PUT',
            '/interruption_reports/999',
            [
                'clinical_tutor_id' => 789,
                'notes' => 'Second interruption report notes modified', // --> modified
            ]
        )
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ])
            ->seeStatusCode(404)
            ->notSeeInDatabase('interruption_reports', ['id' => 999])
            ->notSeeInDatabase('interruption_reports', ['id' => 3]);

        // Invalid ID
        $this->json('PUT',
            '/interruption_reports/abc',
            [
                'clinical_tutor_id' => 789,
                'notes' => 'Second interruption report notes modified', // --> modified
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
            ->notSeeInDatabase('interruption_reports', ['id' => 'abc'])
            ->notSeeInDatabase('interruption_reports', ['id' => 3]);
            
        // Different clinical tutor
        $this->json('PUT',
            '/interruption_reports/2',
            [
                'clinical_tutor_id' => 123,  // --> modified
                'notes' => 'Second interruption report notes',
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
            ->seeInDatabase('interruption_reports', ['id' => 2, 'clinical_tutor_id' => 789])
            ->notSeeInDatabase('interruption_reports', ['id' => 2, 'clinical_tutor_id' => 123]);
            
        // Missing required clinical_tutor_id
        $this->json('PUT',
            '/interruption_reports/2',
            [
                'notes' => 'Another interruption report notes', // --> modified
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
            ->seeInDatabase('interruption_reports', ['id' => 2, 'notes' => 'Second interruption report notes'])
            ->notSeeInDatabase('interruption_reports', ['id' => 2, 'notes' => 'Another interruption report notes']);
            
        // Missing required notes
        $this->json('PUT',
            '/interruption_reports/2',
            [
                'clinical_tutor_id' => 123, // --> modified
            ]
        )
            ->seeJsonEquals([
                'status_code' => 400,
                'status' => 'Bad Request',
                'message' => 'Request is not valid',
                'data' => [
                    'notes' => [
                        'code error_required',
                        'in body',
                    ]
                ]
            ])
            ->seeStatusCode(400)
            ->seeInDatabase('interruption_reports', ['id' => 2, 'clinical_tutor_id' => 789])
            ->notSeeInDatabase('interruption_reports', ['id' => 2, 'clinical_tutor_id' => 123]);
        
        // @todo add further tests related to invalid attribute format

    }
    
    /**
     * Delete a stage interruption report: success.
     */
    public function testDeleteById()
    {

        // Existing stage
        $this->json('DELETE', '/interruption_reports/2')
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource deleted',
            ])
            ->seeStatusCode(200)
            ->seeInDatabase('interruption_reports', ['id' => 2, 'deleted_at' => date('Y-m-d H:i:s')])
            ->notSeeInDatabase('interruption_reports', ['id' => 2, 'deleted_at' => null]);

    }

    /**
     * Delete a stage interruption report: failure.
     */
    public function testDeleteByIdFailure()
    {

        // Non existing interruption report
        $this->json('DELETE', '/interruption_reports/999')
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ])
            ->seeStatusCode(404)
            ->notSeeInDatabase('interruption_reports', ['id' => 999]);

        // Invalid ID
        $this->json('DELETE', '/interruption_reports/abc')
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
            ->notSeeInDatabase('interruption_reports', ['id' => 'abc']);

    }

}
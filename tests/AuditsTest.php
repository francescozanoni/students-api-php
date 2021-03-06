<?php
declare(strict_types=1);

class AuditsTest extends TestCase
{

    /**
     * Get annotation(s) with audits.
     */
    public function testGetAnnotations()
    {

        $john = (new StudentBuilder('john'))->build();

        $this->json('GET', '/annotations?with_audits=true')
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource(s) found',
                'data' => [
                    [
                        'id' => 1,
                        'title' => 'First title',
                        'content' => 'First content',
                        'student' => $john,
                        'audits' => [
                            [
                                'id' => 6,
                                'event' => 'created',
                                'new_values' => [
                                    'title' => 'First title',
                                    'content' => 'First content',
                                    'student_id' => 1,
                                    'id' => 1,
                                ],
                                'user_id' => 0,
                                'created_at' => '2019-01-01 01:00:00',
                            ],
                        ],
                    ],
                ],
            ])
            ->seeStatusCode(200);

        $this->json('GET', '/students/1/annotations?with_audits=true')
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource(s) found',
                'data' => [
                    [
                        'id' => 1,
                        'title' => 'First title',
                        'content' => 'First content',
                        'audits' => [
                            [
                                'id' => 6,
                                'event' => 'created',
                                'new_values' => [
                                    'title' => 'First title',
                                    'content' => 'First content',
                                    'student_id' => 1,
                                    'id' => 1,
                                ],
                                'user_id' => 0,
                                'created_at' => '2019-01-01 01:00:00',
                            ],
                        ],
                    ],
                ],
            ])
            ->seeStatusCode(200);

        $this->json('GET', '/annotations/1?with_audits=true')
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource successfully retrieved/created/modified',
                'data' => [
                    'id' => 1,
                    'title' => 'First title',
                    'content' => 'First content',
                    'student' => $john,
                    'audits' => [
                        [
                            'id' => 6,
                            'event' => 'created',
                            'new_values' => [
                                'title' => 'First title',
                                'content' => 'First content',
                                'student_id' => 1,
                                'id' => 1,
                            ],
                            'user_id' => 0,
                            'created_at' => '2019-01-01 01:00:00',
                        ],
                    ],
                ],
            ])
            ->seeStatusCode(200);

    }

    public function testModifyAnnotation()
    {

        $john = (new StudentBuilder('john'))->build();

        $this->json('PUT',
            '/annotations/1',
            [
                'title' => 'First title modified',
                'content' => 'First content',
            ],
            [
                'x-user-id' => 123,
            ]
        )
            ->seeStatusCode(200);

        $this->json('GET', '/annotations/1?with_audits=true')
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource successfully retrieved/created/modified',
                'data' => [
                    'id' => 1,
                    'title' => 'First title modified',
                    'content' => 'First content',
                    'student' => $john,
                    'audits' => [
                        [
                            'id' => 6,
                            'event' => 'created',
                            'new_values' => [
                                'title' => 'First title',
                                'content' => 'First content',
                                'student_id' => 1,
                                'id' => 1,
                            ],
                            'user_id' => 0,
                            'created_at' => '2019-01-01 01:00:00',
                        ],
                        [
                            'id' => app('db')->table('audits')->count(),
                            'event' => 'updated',
                            'old_values' => [
                                'title' => 'First title',
                            ],
                            'new_values' => [
                                'title' => 'First title modified',
                            ],
                            'user_id' => 123,
                            'created_at' => date('Y-m-d H:i:s'),
                        ],
                    ],
                ],
            ])
            ->seeStatusCode(200);

    }

    /**
     * Get educational activity attendance(s) with audits.
     */
    public function testGetEducationalActivityAttendances()
    {

        $john = (new StudentBuilder('john'))->build();

        $this->json('GET', '/educational_activity_attendances?with_audits=true')
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
                        'student' => $john,
                        'audits' => [
                            [
                                'id' => 18,
                                'event' => 'created',
                                'new_values' => [
                                    'educational_activity' => 'First educational activity',
                                    'start_date' => '2019-01-08',
                                    'end_date' => '2019-01-09',
                                    'credits' => 1.2,
                                    'student_id' => 1,
                                    'id' => 1,
                                ],
                                'user_id' => 0,
                                'created_at' => '2019-01-10 01:00:00',
                            ],
                        ],
                    ],
                ],
            ])
            ->seeStatusCode(200);

        $this->json('GET', '/students/1/educational_activity_attendances?with_audits=true')
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
                        'audits' => [
                            [
                                'id' => 18,
                                'event' => 'created',
                                'new_values' => [
                                    'educational_activity' => 'First educational activity',
                                    'start_date' => '2019-01-08',
                                    'end_date' => '2019-01-09',
                                    'credits' => 1.2,
                                    'student_id' => 1,
                                    'id' => 1,
                                ],
                                'user_id' => 0,
                                'created_at' => '2019-01-10 01:00:00',
                            ],
                        ],
                    ],
                ],
            ])
            ->seeStatusCode(200);

        $this->json('GET', '/educational_activity_attendances/1?with_audits=true')
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
                    'student' => $john,
                    'audits' => [
                        [
                            'id' => 18,
                            'event' => 'created',
                            'new_values' => [
                                'educational_activity' => 'First educational activity',
                                'start_date' => '2019-01-08',
                                'end_date' => '2019-01-09',
                                'credits' => 1.2,
                                'student_id' => 1,
                                'id' => 1,
                            ],
                            'user_id' => 0,
                            'created_at' => '2019-01-10 01:00:00',
                        ],
                    ],
                ],
            ])
            ->seeStatusCode(200);

    }

    /**
     * Get eligibility(ies) with audits.
     */
    public function testGetEligibilities()
    {

        $john = (new StudentBuilder('john'))->build();
        $jane = (new StudentBuilder('jane'))->build();

        $this->json('GET', '/eligibilities?with_audits=true')
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
                        'student' => $john,
                        'audits' => [
                            [
                                'id' => 10,
                                'event' => 'created',
                                'new_values' => [
                                    'start_date' => '2019-01-01',
                                    'end_date' => '2019-12-01',
                                    'notes' => 'First eligibility notes',
                                    'is_eligible' => true,
                                    'student_id' => 1,
                                    'id' => 2,
                                ],
                                'user_id' => 0,
                                'created_at' => '2019-01-02 02:00:00',
                            ],
                        ],
                    ],
                    [
                        'id' => 3,
                        'start_date' => '2019-01-01',
                        'end_date' => '2019-12-01',
                        'is_eligible' => false,
                        'student' => $jane,
                        'audits' => [
                            [
                                'id' => 11,
                                'event' => 'created',
                                'new_values' => [
                                    'start_date' => '2019-01-01',
                                    'end_date' => '2019-12-01',
                                    'is_eligible' => false,
                                    'student_id' => 2,
                                    'id' => 3,
                                ],
                                'user_id' => 0,
                                'created_at' => '2019-01-02 03:00:00',
                            ],
                        ],
                    ],
                ],
            ])
            ->seeStatusCode(200);

        $this->json('GET', '/students/1/eligibilities?with_audits=true')
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
                        'audits' => [
                            [
                                'id' => 10,
                                'event' => 'created',
                                'new_values' => [
                                    'start_date' => '2019-01-01',
                                    'end_date' => '2019-12-01',
                                    'notes' => 'First eligibility notes',
                                    'is_eligible' => true,
                                    'student_id' => 1,
                                    'id' => 2,
                                ],
                                'user_id' => 0,
                                'created_at' => '2019-01-02 02:00:00',
                            ],
                        ],
                    ],
                ],
            ])
            ->seeStatusCode(200);

        $this->json('GET', '/eligibilities/2?with_audits=true')
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
                    'student' => $john,
                    'audits' => [
                        [
                            'id' => 10,
                            'event' => 'created',
                            'new_values' => [
                                'start_date' => '2019-01-01',
                                'end_date' => '2019-12-01',
                                'notes' => 'First eligibility notes',
                                'is_eligible' => true,
                                'student_id' => 1,
                                'id' => 2,
                            ],
                            'user_id' => 0,
                            'created_at' => '2019-01-02 02:00:00',
                        ],
                    ],
                ],
            ])
            ->seeStatusCode(200);

    }

    /**
     * Get internship evaluation(s) with audits.
     */
    public function testGetEvaluations()
    {

        $john = (new StudentBuilder('john'))->build();

        $this->json('GET', '/evaluations?with_audits=true')
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
                                'student' => $john,
                                'start_date' => '2019-01-10',
                                'end_date' => '2019-01-24',
                                'hour_amount' => 123,
                                'other_amount' => 5,
                                'is_optional' => false,
                                'is_interrupted' => false
                            ],
                            'notes' => 'First evaluation notes',
                            'audits' => [
                                [
                                    'id' => 22,
                                    'event' => 'created',
                                    'new_values' => array_merge(
                                        [
                                            'notes' => 'First evaluation notes',
                                            'internship_id' => 1,
                                            'id' => 1
                                        ],
                                        EvaluationsTableSeeder::generateItemValues($this->app['config']['internships']['evaluations']['items'])
                                    ),
                                    'user_id' => 0,
                                    'created_at' => '2019-01-25 02:00:00'
                                ]
                            ],
                        ],
                        EvaluationsTableSeeder::generateItemValues($this->app['config']['internships']['evaluations']['items'])
                    ),
                ],
            ])
            ->seeStatusCode(200);

        $this->json('GET', '/internships/1/evaluation?with_audits=true')
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource successfully retrieved/created/modified',
                'data' => array_merge(
                    [
                        'id' => 1,
                        'notes' => 'First evaluation notes',
                        'audits' => [
                            [
                                'id' => 22,
                                'event' => 'created',
                                'new_values' => array_merge(
                                    [
                                        'notes' => 'First evaluation notes',
                                        'internship_id' => 1,
                                        'id' => 1
                                    ],
                                    EvaluationsTableSeeder::generateItemValues($this->app['config']['internships']['evaluations']['items'])
                                ),
                                'user_id' => 0,
                                'created_at' => '2019-01-25 02:00:00'
                            ]
                        ],
                    ],
                    EvaluationsTableSeeder::generateItemValues($this->app['config']['internships']['evaluations']['items'])
                ),
            ])
            ->seeStatusCode(200);

        $this->json('GET', '/evaluations/1?with_audits=true')
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
                            'student' => $john,
                            'start_date' => '2019-01-10',
                            'end_date' => '2019-01-24',
                            'hour_amount' => 123,
                            'other_amount' => 5,
                            'is_optional' => false,
                            'is_interrupted' => false
                        ],
                        'notes' => 'First evaluation notes',
                        'audits' => [
                            [
                                'id' => 22,
                                'event' => 'created',
                                'new_values' => array_merge(
                                    [
                                        'notes' => 'First evaluation notes',
                                        'internship_id' => 1,
                                        'id' => 1
                                    ],
                                    EvaluationsTableSeeder::generateItemValues($this->app['config']['internships']['evaluations']['items'])
                                ),
                                'user_id' => 0,
                                'created_at' => '2019-01-25 02:00:00'
                            ]
                        ],
                    ],
                    EvaluationsTableSeeder::generateItemValues($this->app['config']['internships']['evaluations']['items'])
                ),
            ])
            ->seeStatusCode(200);

    }

    /**
     * Get internship(s) with audits.
     */
    public function testGetInternships()
    {

        $john = (new StudentBuilder('john'))->build();
        $joan = (new StudentBuilder('joan'))->build();

        $this->json('GET', '/internships?with_audits=true')
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource(s) found',
                'data' => [
                    [
                        'id' => 1,
                        'location' => 'Location 1',
                        'sub_location' => 'Sub-location 1',
                        'student' => $john,
                        'start_date' => '2019-01-10',
                        'end_date' => '2019-01-24',
                        'hour_amount' => 123,
                        'other_amount' => 5,
                        'is_optional' => false,
                        'is_interrupted' => false,
                        'audits' => [
                            [
                                'id' => 17,
                                'event' => 'created',
                                'new_values' => [
                                    'student_id' => 1,
                                    'location_id' => 1,
                                    'sub_location_id' => 1,
                                    'start_date' => '2019-01-10',
                                    'end_date' => '2019-01-24',
                                    'hour_amount' => 123,
                                    'other_amount' => 5,
                                    'is_optional' => false,
                                    'is_interrupted' => false,
                                    'id' => 1,
                                ],
                                'user_id' => 0,
                                'created_at' => '2019-01-09 02:00:00',
                            ],
                        ],
                    ],
                    [
                        'id' => 2,
                        'location' => 'Location 1',
                        'sub_location' => 'Sub-location 1',
                        'student' => $john,
                        'start_date' => '2019-01-26',
                        'end_date' => '2019-01-31',
                        'hour_amount' => 34,
                        'other_amount' => 0,
                        'is_optional' => true,
                        'is_interrupted' => true,
                        'audits' => [
                            [
                                'id' => 21,
                                'event' => 'created',
                                'new_values' => [
                                    'student_id' => 1,
                                    'location_id' => 1,
                                    'sub_location_id' => 1,
                                    'start_date' => '2019-01-26',
                                    'end_date' => '2019-01-31',
                                    'hour_amount' => 34,
                                    'other_amount' => 0,
                                    'is_optional' => true,
                                    'is_interrupted' => true,
                                    'id' => 2,
                                ],
                                'user_id' => 0,
                                'created_at' => '2019-01-25 02:00:00',
                            ],
                        ],
                    ],
                    [
                        'id' => 3,
                        'location' => 'Location 1',
                        'sub_location' => 'Sub-location 1',
                        'student' => $joan,
                        'start_date' => '2019-01-26',
                        'end_date' => '2019-01-31',
                        'hour_amount' => 34,
                        'other_amount' => 0,
                        'is_optional' => false,
                        'is_interrupted' => true,
                        'audits' => [
                            [
                                'id' => 20,
                                'event' => 'created',
                                'new_values' => [
                                    'student_id' => 4,
                                    'location_id' => 1,
                                    'sub_location_id' => 1,
                                    'start_date' => '2019-01-26',
                                    'end_date' => '2019-01-31',
                                    'hour_amount' => 34,
                                    'other_amount' => 0,
                                    'is_optional' => false,
                                    'is_interrupted' => false,
                                    'id' => 3,
                                ],
                                'user_id' => 0,
                                'created_at' => '2019-01-25 02:00:00',
                            ],
                            [
                                'id' => 26,
                                'event' => 'updated',
                                'old_values' => [
                                    'is_interrupted' => false,
                                ],
                                'new_values' => [
                                    'is_interrupted' => true,
                                ],
                                'user_id' => 0,
                                'created_at' => '2019-01-28 02:00:00',
                            ],
                        ],
                    ],
                    [
                        'id' => 4,
                        'location' => 'Location 1',
                        'sub_location' => 'Sub-location 1',
                        'student' => $joan,
                        // 10 days in the future.
                        'start_date' => (new DateTime())->add(new DateInterval('P10D'))->format('Y-m-d'),
                        // 20 days in the future.
                        'end_date' => (new DateTime())->add(new DateInterval('P20D'))->format('Y-m-d'),
                        'hour_amount' => 0,
                        'other_amount' => 0,
                        'is_optional' => false,
                        'is_interrupted' => true,
                        'audits' => [
                            [
                                'id' => 19,
                                'event' => 'created',
                                'new_values' => [
                                    'student_id' => 4,
                                    'location_id' => 1,
                                    'sub_location_id' => 1,
                                    // 10 days in the future.
                                    'start_date' => (new DateTime())->add(new DateInterval('P10D'))->format('Y-m-d'),
                                    // 20 days in the future.
                                    'end_date' => (new DateTime())->add(new DateInterval('P20D'))->format('Y-m-d'),
                                    'hour_amount' => 0,
                                    'other_amount' => 0,
                                    'is_optional' => false,
                                    'is_interrupted' => false,
                                    'id' => 4,
                                ],
                                'user_id' => 0,
                                'created_at' => '2019-01-25 02:00:00',
                            ],
                            [
                                'id' => 25,
                                'event' => 'updated',
                                'old_values' => [
                                    'is_interrupted' => false,
                                ],
                                'new_values' => [
                                    'is_interrupted' => true,
                                ],
                                'user_id' => 0,
                                'created_at' => '2019-01-28 02:00:00',
                            ],
                        ],
                    ],
                ]
            ])
            ->seeStatusCode(200);

        $this->json('GET', '/students/1/internships?with_audits=true')
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
                        'is_interrupted' => false,
                        'audits' => [
                            [
                                'id' => 17,
                                'event' => 'created',
                                'new_values' => [
                                    'student_id' => 1,
                                    'location_id' => 1,
                                    'sub_location_id' => 1,
                                    'start_date' => '2019-01-10',
                                    'end_date' => '2019-01-24',
                                    'hour_amount' => 123,
                                    'other_amount' => 5,
                                    'is_optional' => false,
                                    'is_interrupted' => false,
                                    'id' => 1,
                                ],
                                'user_id' => 0,
                                'created_at' => '2019-01-09 02:00:00',
                            ],
                        ],
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
                        'is_interrupted' => true,
                        'audits' => [
                            [
                                'id' => 21,
                                'event' => 'created',
                                'new_values' => [
                                    'student_id' => 1,
                                    'location_id' => 1,
                                    'sub_location_id' => 1,
                                    'start_date' => '2019-01-26',
                                    'end_date' => '2019-01-31',
                                    'hour_amount' => 34,
                                    'other_amount' => 0,
                                    'is_optional' => true,
                                    'is_interrupted' => true,
                                    'id' => 2,
                                ],
                                'user_id' => 0,
                                'created_at' => '2019-01-25 02:00:00',
                            ],
                        ],
                    ],
                ],
            ])
            ->seeStatusCode(200);

        $this->json('GET', '/internships/3?with_audits=true')
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource successfully retrieved/created/modified',
                'data' => [
                    'id' => 3,
                    'location' => 'Location 1',
                    'sub_location' => 'Sub-location 1',
                    'student' => $joan,
                    'start_date' => '2019-01-26',
                    'end_date' => '2019-01-31',
                    'hour_amount' => 34,
                    'other_amount' => 0,
                    'is_optional' => false,
                    'is_interrupted' => true,
                    'audits' => [
                        [
                            'id' => 20,
                            'event' => 'created',
                            'new_values' => [
                                'student_id' => 4,
                                'location_id' => 1,
                                'sub_location_id' => 1,
                                'start_date' => '2019-01-26',
                                'end_date' => '2019-01-31',
                                'hour_amount' => 34,
                                'other_amount' => 0,
                                'is_optional' => false,
                                'is_interrupted' => false,
                                'id' => 3,
                            ],
                            'user_id' => 0,
                            'created_at' => '2019-01-25 02:00:00',
                        ],
                        [
                            'id' => 26,
                            'event' => 'updated',
                            'old_values' => [
                                'is_interrupted' => false,
                            ],
                            'new_values' => [
                                'is_interrupted' => true,
                            ],
                            'user_id' => 0,
                            'created_at' => '2019-01-28 02:00:00',
                        ],
                    ],
                ],
            ])
            ->seeStatusCode(200);

    }

    /**
     * Get internship interruption report(s) with audits.
     */
    public function testGetInterruptionReports()
    {

        $john = (new StudentBuilder('john'))->build();

        $this->json('GET', '/interruption_reports?with_audits=true')
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource(s) found',
                'data' => [
                    [
                        'id' => 2,
                        'notes' => 'Second interruption report notes',
                        'internship' => [
                            'id' => 2,
                            'location' => 'Location 1',
                            'sub_location' => 'Sub-location 1',
                            'student' => $john,
                            'start_date' => '2019-01-26',
                            'end_date' => '2019-01-31',
                            'hour_amount' => 34,
                            'other_amount' => 0,
                            'is_optional' => true,
                            'is_interrupted' => true
                        ],
                        'audits' => [
                            [
                                'id' => 29,
                                'event' => 'created',
                                'new_values' => [
                                    'notes' => 'Second interruption report notes',
                                    'internship_id' => 2,
                                    'id' => 2
                                ],
                                'user_id' => 0,
                                'created_at' => '2019-01-31 02:00:00'
                            ]
                        ],
                    ],
                ],
            ])
            ->seeStatusCode(200);

        $this->json('GET', '/internships/2/interruption_report?with_audits=true')
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource successfully retrieved/created/modified',
                'data' => [
                    'id' => 2,
                    'notes' => 'Second interruption report notes',
                    'audits' => [
                        [
                            'id' => 29,
                            'event' => 'created',
                            'new_values' => [
                                'notes' => 'Second interruption report notes',
                                'internship_id' => 2,
                                'id' => 2
                            ],
                            'user_id' => 0,
                            'created_at' => '2019-01-31 02:00:00'
                        ]
                    ],
                ],
            ])
            ->seeStatusCode(200);

        $this->json('GET', '/interruption_reports/2?with_audits=true')
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource successfully retrieved/created/modified',
                'data' => [
                    'id' => 2,
                    'notes' => 'Second interruption report notes',
                    'internship' => [
                        'id' => 2,
                        'location' => 'Location 1',
                        'sub_location' => 'Sub-location 1',
                        'student' => $john,
                        'start_date' => '2019-01-26',
                        'end_date' => '2019-01-31',
                        'hour_amount' => 34,
                        'other_amount' => 0,
                        'is_optional' => true,
                        'is_interrupted' => true
                    ],
                    'audits' => [
                        [
                            'id' => 29,
                            'event' => 'created',
                            'new_values' => [
                                'notes' => 'Second interruption report notes',
                                'internship_id' => 2,
                                'id' => 2
                            ],
                            'user_id' => 0,
                            'created_at' => '2019-01-31 02:00:00'
                        ]
                    ],
                ],
            ])
            ->seeStatusCode(200);

    }

    /**
     * Get Occupational Safety and Health course attendance(s) with audits.
     */
    public function testGetOshCourseAttendances()
    {

        $john = (new StudentBuilder('john'))->build();
        $joan = (new StudentBuilder('joan'))->build();

        $this->json('GET', '/osh_course_attendances?with_audits=true')
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource(s) found',
                'data' => [
                    [
                        'id' => 1,
                        'start_date' => '2018-12-08',
                        'end_date' => '2019-12-07',
                        'student' => $john,
                        'audits' => [
                            [
                                'id' => 2,
                                'event' => 'created',
                                'new_values' => [
                                    'start_date' => '2018-12-08',
                                    'end_date' => '2019-12-07',
                                    'student_id' => 1,
                                    'id' => 1,
                                ],
                                'user_id' => 0,
                                'created_at' => '2018-12-10 01:00:00',
                            ],
                        ],
                    ],
                    [
                        'id' => 3,
                        'start_date' => '2018-12-14',
                        'end_date' => '2019-12-13',
                        'student' => $joan,
                        'audits' => [
                            [
                                'id' => 4,
                                'event' => 'created',
                                'new_values' => [
                                    'start_date' => '2018-12-14',
                                    'end_date' => '2019-12-13',
                                    'student_id' => 4,
                                    'id' => 3,
                                ],
                                'user_id' => 0,
                                'created_at' => '2018-12-15 01:00:00',
                            ],
                        ],
                    ],
                ],
            ])
            ->seeStatusCode(200);

        $this->json('GET', '/students/1/osh_course_attendances?with_audits=true')
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource(s) found',
                'data' => [
                    [
                        'id' => 1,
                        'start_date' => '2018-12-08',
                        'end_date' => '2019-12-07',
                        'audits' => [
                            [
                                'id' => 2,
                                'event' => 'created',
                                'new_values' => [
                                    'start_date' => '2018-12-08',
                                    'end_date' => '2019-12-07',
                                    'student_id' => 1,
                                    'id' => 1,
                                ],
                                'user_id' => 0,
                                'created_at' => '2018-12-10 01:00:00',
                            ],
                        ],
                    ],
                ],
            ])
            ->seeStatusCode(200);

        $this->json('GET', '/osh_course_attendances/1?with_audits=true')
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource successfully retrieved/created/modified',
                'data' => [
                    'id' => 1,
                    'start_date' => '2018-12-08',
                    'end_date' => '2019-12-07',
                    'student' => $john,
                    'audits' => [
                        [
                            'id' => 2,
                            'event' => 'created',
                            'new_values' => [
                                'start_date' => '2018-12-08',
                                'end_date' => '2019-12-07',
                                'student_id' => 1,
                                'id' => 1,
                            ],
                            'user_id' => 0,
                            'created_at' => '2018-12-10 01:00:00',
                        ],
                    ],
                ],
            ])
            ->seeStatusCode(200);

    }

    /**
     * Get student(s) with audits.
     */
    public function testGetStudents()
    {

        $john = (new StudentBuilder('john'))->build();
        $jane = (new StudentBuilder('jane'))->build();
        $joan = (new StudentBuilder('joan'))->build();

        $johnWithAudits = (new StudentBuilder('john'))
            ->with(
                'audits',
                [
                    [
                        'id' => 5,
                        'event' => 'created',
                        'new_values' => $john,
                        'user_id' => 0,
                        'created_at' => '2019-01-01 00:00:00'
                    ]
                ]
            )
            ->build();
        $janeWithAudits = (new StudentBuilder('jane'))
            ->with(
                'audits',
                [
                    [
                        'id' => 7,
                        'event' => 'created',
                        'new_values' => $jane,
                        'user_id' => 0,
                        'created_at' => '2019-01-02 00:00:00'
                    ]
                ]
            )
            ->build();
        $joanWithAudits = (new StudentBuilder('joan'))
            ->with(
                'audits',
                [
                    [
                        'id' => 14,
                        'event' => 'created',
                        'new_values' => $joan,
                        'user_id' => 0,
                        'created_at' => '2019-01-05 00:00:00'
                    ]
                ]
            )
            ->build();

        $this->json('GET', '/students?with_audits=true')
            ->seeJsonEquals([
                    'status_code' => 200,
                    'status' => 'OK',
                    'message' => 'Resource(s) found',
                    'data' => [
                        $johnWithAudits,
                        $janeWithAudits,
                        $joanWithAudits
                    ]
                ]
            )
            ->seeStatusCode(200);

        $this->json('GET', '/students/1?with_audits=true')
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource successfully retrieved/created/modified',
                'data' => $johnWithAudits
            ])
            ->seeStatusCode(200);

    }

}
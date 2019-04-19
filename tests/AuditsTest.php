<?php
declare(strict_types = 1);

class AuditsTest extends TestCase
{

    /**
     * Get annotation(s) with audits.
     */
    public function testGetAnnotations()
    {

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
                        'student' => [
                            'id' => 1,
                            'first_name' => 'John',
                            'last_name' => 'Doe',
                            'e_mail' => 'john.doe@foo.com',
                            'phone' => '1234-567890',
                            'nationality' => 'GB',
                        ],
                        "audits" => [
                            [
                                "id" => 6,
                                "event" => "created",
                                "new_values" => [
                                    "title" => "First title",
                                    "content" => "First content",
                                    "student_id" => 1,
                                    "id" => 1,
                                ],
                                "user_id" => 0,
                                "created_at" => "2019-01-01 01:00:00",
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
                        "audits" => [
                            [
                                "id" => 6,
                                "event" => "created",
                                "new_values" => [
                                    "title" => "First title",
                                    "content" => "First content",
                                    "student_id" => 1,
                                    "id" => 1,
                                ],
                                "user_id" => 0,
                                "created_at" => "2019-01-01 01:00:00",
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
                    'student' => [
                        'id' => 1,
                        'first_name' => 'John',
                        'last_name' => 'Doe',
                        'e_mail' => 'john.doe@foo.com',
                        'phone' => '1234-567890',
                        'nationality' => 'GB',
                    ],
                    "audits" => [
                        [
                            "id" => 6,
                            "event" => "created",
                            "new_values" => [
                                "title" => "First title",
                                "content" => "First content",
                                "student_id" => 1,
                                "id" => 1,
                            ],
                            "user_id" => 0,
                            "created_at" => "2019-01-01 01:00:00",
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
                        'student' => [
                            'id' => 1,
                            'first_name' => 'John',
                            'last_name' => 'Doe',
                            'e_mail' => 'john.doe@foo.com',
                            'phone' => '1234-567890',
                            'nationality' => 'GB',
                        ],
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
                    'student' => [
                        'id' => 1,
                        'first_name' => 'John',
                        'last_name' => 'Doe',
                        'e_mail' => 'john.doe@foo.com',
                        'phone' => '1234-567890',
                        'nationality' => 'GB',
                    ],
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
     * Get all eligibility(ies) with audits.
     */
    public function testGetEligibilities()
    {

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
                        'student' => [
                            'id' => 1,
                            'first_name' => 'John',
                            'last_name' => 'Doe',
                            'e_mail' => 'john.doe@foo.com',
                            'phone' => '1234-567890',
                            'nationality' => 'GB',
                        ],
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
                        'student' => [
                            'id' => 2,
                            'first_name' => 'Jane',
                            'last_name' => 'Doe',
                            'e_mail' => 'jane.doe@bar.com',
                            'nationality' => 'CA',
                        ],
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
                    'student' => [
                        'id' => 1,
                        'first_name' => 'John',
                        'last_name' => 'Doe',
                        'e_mail' => 'john.doe@foo.com',
                        'phone' => '1234-567890',
                        'nationality' => 'GB',
                    ],
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

}
<?php
declare(strict_types = 1);

class StagesTest extends TestCase
{

    /**
     * Get all stages.
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
                ]
            ])
            ->seeStatusCode(200);
    }

}

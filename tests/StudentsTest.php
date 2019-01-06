<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
//use Illuminate\Database\Seeder\DatabaseSeeder;

class StudentsTest extends TestCase
{

    use DatabaseMigrations;
    
    public function setUp()
    {
        parent::setUp();
        app(DatabaseSeeder::class)->run();
    }
    
    /**
     * List students.
     */
    public function testList()
    {
        $this->get('/students')
        ->seeJsonEquals([
                [
                'id' => 1,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'e_mail' => 'john.doe@foo.com',
            'phone' => '1234-567890',
            'nationality' => 'UK',
            'created_at' => '2019-01-01 00:00:00',
            'updated_at' => '2019-01-01 00:00:00',
            'deleted_at' => null,
        ],
        [
                'id' => 2,
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'e_mail' => 'jane.doe@bar.com',
            'phone' => null,
            'nationality' => 'CA',
            'created_at' => '2019-01-02 00:00:00',
            'updated_at' => '2019-01-02 00:00:00',
            'deleted_at' => null,
        ]
             ]);
    }

}

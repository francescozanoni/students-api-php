<?php

use Laravel\Lumen\Testing\DatabaseMigrations;

class StudentsTest extends TestCase
{

    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();
        app(DatabaseSeeder::class)->run();
    }

    /**
     * Get all students.
     */
    public function testGet()
    {
        $this->get('/students')
            ->seeStatusCode(200)
            ->seeJsonEquals([
                [
                    'id' => 1,
                    'first_name' => 'John',
                    'last_name' => 'Doe',
                    'e_mail' => 'john.doe@foo.com',
                    'phone' => '1234-567890',
                    'nationality' => 'UK',
                ],
                [
                    'id' => 2,
                    'first_name' => 'Jane',
                    'last_name' => 'Doe',
                    'e_mail' => 'jane.doe@bar.com',
                    'phone' => null,
                    'nationality' => 'CA',
                ],
            ]);
    }

    /**
     * Get a student.
     */
    public function testGetById()
    {
        $this->get('/students/1')
            ->seeStatusCode(200)
            ->seeJsonEquals([
                'id' => 1,
                'first_name' => 'John',
                'last_name' => 'Doe',
                'e_mail' => 'john.doe@foo.com',
                'phone' => '1234-567890',
                'nationality' => 'UK',
            ]);
    }

    /**
     * Create a student.
     */
    public function testCreate()
    {
        $this->post(
            '/students',
            [
                'first_name' => 'Jack',
                'last_name' => 'Doe',
                'e_mail' => 'jack.doe@faz.com',
                'phone' => '0000-11111111',
                'nationality' => 'AU',
            ]
        )
            ->seeStatusCode(200)
            ->seeJsonEquals([
                'id' => 4,
                'first_name' => 'Jack',
                'last_name' => 'Doe',
                'e_mail' => 'jack.doe@faz.com',
                'phone' => '0000-11111111',
                'nationality' => 'AU',
            ]);
    }

    /**
     * Modify a student.
     */
    public function testModifyById()
    {
        $this->put(
            '/students/2',
            [
                'id' => 2,
                'first_name' => 'Jane',
                'last_name' => 'Doe',
                'e_mail' => 'jane.doe@bar.com',
                'phone' => '3333-11111111',
                'nationality' => 'IE',
            ]
        )
            ->seeStatusCode(200)
            ->seeJsonEquals([
                'id' => 2,
                'first_name' => 'Jane',
                'last_name' => 'Doe',
                'e_mail' => 'jane.doe@bar.com',
                'phone' => '3333-11111111',
                'nationality' => 'IE',
            ]);
    }

    /**
     * Delete a student.
     */
    public function testDeleteById()
    {
        $this->delete('/students/2')
            ->seeStatusCode(200)
            ->notSeeInDatabase('students', ['id' => 2, 'deleted_at' => null]);
    }

}

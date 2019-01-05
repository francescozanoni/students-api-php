<?php

use Laravel\Lumen\Testing\DatabaseMigrations;

class StudentsTest extends TestCase
{

    use DatabaseMigrations;
    
    /**
     * List students.
     */
    public function testList()
    {
        $this->get('/');

        $this->assertEquals(
            $this->app->version(), $this->response->getContent()
        );
    }

}

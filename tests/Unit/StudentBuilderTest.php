<?php
declare(strict_types = 1);

class StudentBuilderTest extends TestCase
{

    public function testBase()
    {
        $this->assertEquals(
            [
                'id' => 1,
                'first_name' => 'John',
                'last_name' => 'Doe',
                'e_mail' => 'john.doe@foo.com',
                'phone' => '1234-567890',
                'nationality' => 'GB',
            ],
            (new StudentBuilder('john'))->build()
        );
    }

    public function testWithChangedProperty()
    {
        $this->assertEquals(
            [
                'id' => 1,
                'first_name' => 'John',
                'last_name' => 'Doe',
                'e_mail' => 'john.doe@foo.com',
                'phone' => '1234-567890',
                'nationality' => 'IT',
            ],
            (new StudentBuilder('john'))->with('nationality', 'IT')->build()
        );
    }

    public function testWithAddedProperty()
    {
        $this->assertEquals(
            [
                'id' => 1,
                'first_name' => 'John',
                'last_name' => 'Doe',
                'e_mail' => 'john.doe@foo.com',
                'phone' => '1234-567890',
                'nationality' => 'GB',
                'new_property' => 'new value',
            ],
            (new StudentBuilder('john'))->with('new_property', 'new value')->build()
        );
    }

    public function testWithoutProperty()
    {
        $this->assertEquals(
            [
                'id' => 1,
                'first_name' => 'John',
                'last_name' => 'Doe',
                'e_mail' => 'john.doe@foo.com',
                'nationality' => 'GB',
            ],
            (new StudentBuilder('john'))->without('phone')->build()
        );
    }

    public function testInexistentAlias()
    {
        $this->assertEquals(
            [],
            (new StudentBuilder('inexistent_alias'))->build()
        );
    }

}
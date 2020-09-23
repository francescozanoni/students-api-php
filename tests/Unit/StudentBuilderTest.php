<?php
declare(strict_types=1);

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
                'text_field_a' => 'TEXT FIELD A VALUE',
                'text_field_b' => 'TEXT FIELD B VALUE',
                'date_field_a' => '2020-01-01',
                'date_field_b' => '2020-01-31',
                'amount_field_a' => 30,
                'amount_field_b' => 120,
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
                'text_field_a' => 'TEXT FIELD A VALUE',
                'text_field_b' => 'TEXT FIELD B VALUE',
                'date_field_a' => '2020-01-01',
                'date_field_b' => '2020-01-31',
                'amount_field_a' => 30,
                'amount_field_b' => 120,
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
                'text_field_a' => 'TEXT FIELD A VALUE',
                'text_field_b' => 'TEXT FIELD B VALUE',
                'date_field_a' => '2020-01-01',
                'date_field_b' => '2020-01-31',
                'amount_field_a' => 30,
                'amount_field_b' => 120,
            ],
            (new StudentBuilder('john'))->with('new_property', 'new value')->build()
        );
    }

    public function testWithoutProperties()
    {
        $this->assertEquals(
            [
                'id' => 1,
                'first_name' => 'John',
                'last_name' => 'Doe',
                'e_mail' => 'john.doe@foo.com',
                'phone' => '1234-567890',
                'nationality' => 'GB',
                'text_field_a' => 'TEXT FIELD A VALUE',
                'text_field_b' => 'TEXT FIELD B VALUE',
                'date_field_a' => '2020-01-01',
                'date_field_b' => '2020-01-31',
                'amount_field_a' => 30,
                'amount_field_b' => 120,
            ],
            (new StudentBuilder('john'))->without()->build()
        );
        $this->assertEquals(
            [
                'id' => 1,
                'first_name' => 'John',
                'last_name' => 'Doe',
                'e_mail' => 'john.doe@foo.com',
                'nationality' => 'GB',
                'text_field_a' => 'TEXT FIELD A VALUE',
                'text_field_b' => 'TEXT FIELD B VALUE',
                'date_field_a' => '2020-01-01',
                'date_field_b' => '2020-01-31',
                'amount_field_a' => 30,
                'amount_field_b' => 120,
            ],
            (new StudentBuilder('john'))->without('phone')->build()
        );
        $this->assertEquals(
            [
                'id' => 1,
                'first_name' => 'John',
                'last_name' => 'Doe',
                'e_mail' => 'john.doe@foo.com',
                'text_field_a' => 'TEXT FIELD A VALUE',
                'text_field_b' => 'TEXT FIELD B VALUE',
                'date_field_a' => '2020-01-01',
                'date_field_b' => '2020-01-31',
                'amount_field_a' => 30,
                'amount_field_b' => 120,
            ],
            (new StudentBuilder('john'))->without('phone')->without('nationality')->build()
        );
        $this->assertEquals(
            [
                'id' => 1,
                'first_name' => 'John',
                'last_name' => 'Doe',
                'e_mail' => 'john.doe@foo.com',
                'text_field_a' => 'TEXT FIELD A VALUE',
                'text_field_b' => 'TEXT FIELD B VALUE',
                'date_field_a' => '2020-01-01',
                'date_field_b' => '2020-01-31',
                'amount_field_a' => 30,
                'amount_field_b' => 120,
            ],
            (new StudentBuilder('john'))->without('phone', 'nationality')->build()
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
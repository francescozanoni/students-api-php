<?php
declare(strict_types = 1);

/**
 * Class StudentBuilder
 *
 * Build student data for testing/development purposes.
 *
 * Examples:
 * See AbstractBuilder.
 */
class StudentBuilder extends AbstractBuilder
{

    protected $baseData = [
        'john' => [
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
        'jane' => [
            'id' => 2,
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'e_mail' => 'jane.doe@bar.com',
            'nationality' => 'CA',
            'text_field_a' => 'ANOTHER TEXT FIELD A VALUE',
            'date_field_a' => '2020-02-01',
            'amount_field_a' => 40,
        ],
        'jim' => [
            'id' => 3,
            'first_name' => 'Jim',
            'last_name' => 'Doe',
            'e_mail' => 'jim.doe@baz.com',
            'nationality' => 'US',
        ],
        'joan' => [
            'id' => 4,
            'first_name' => 'Joan',
            'last_name' => 'Doe',
            'e_mail' => 'joan.doe@foo.com',
            'nationality' => 'IE',
            'text_field_b' => 'ANOTHER TEXT FIELD B VALUE',
            'date_field_b' => '2020-02-29',
            'amount_field_b' => 50,
        ],
        'jack' => [
            'id' => 5,
            'first_name' => 'Jack',
            'last_name' => 'Doe',
            'e_mail' => 'jack.doe@faz.com',
            'phone' => '0000-11111111',
            'nationality' => 'AU',
        ],
        'aaa' => [
            'first_name' => 'AAA',
            'last_name' => 'BBB',
            'e_mail' => 'aaa.bbb@ccc.com',
            'phone' => '3333-11111111',
            'nationality' => 'NO',
        ],
    ];

    public function __construct(string $alias)
    {
        return parent::__construct($alias);
    }

}
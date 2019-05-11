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
        ],
        'jane' => [
            'id' => 2,
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'e_mail' => 'jane.doe@bar.com',
            'nationality' => 'CA',
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
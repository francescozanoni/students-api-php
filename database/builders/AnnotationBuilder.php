<?php
declare(strict_types = 1);

/**
 * Class AnnotationBuilder
 *
 * Build annotation data for testing/development purposes.
 *
 * Examples:
 * See AbstractBuilder.
 */
class AnnotationBuilder extends AbstractBuilder
{

    protected $baseData = [
        'first' => [
            'id' => 1,
            'title' => 'First title',
            'content' => 'First content',
        ],
        'second' => [
            'id' => 2,
            'title' => 'Second title',
            'content' => 'Second content',
        ],
    ];

    public function __construct(string $alias)
    {
        return parent::__construct($alias);
    }

}
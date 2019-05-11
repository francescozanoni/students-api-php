<?php
declare(strict_types = 1);

class AnnotationBuilderTest extends TestCase
{

    public function testBase()
    {
        $this->assertEquals(
            [
                'id' => 1,
                'title' => 'First title',
                'content' => 'First content',
            ],
            (new AnnotationBuilder('first'))->build()
        );
    }

    public function testWithChangedProperty()
    {
        $this->assertEquals(
            [
                'id' => 1,
                'title' => 'AAAAAA',
                'content' => 'First content',
            ],
            (new AnnotationBuilder('first'))->with('title', 'AAAAAA')->build()
        );
    }

    public function testWithAddedProperty()
    {
        $this->assertEquals(
            [
                'id' => 1,
                'title' => 'First title',
                'content' => 'First content',
                'new_property' => 'new value',
            ],
            (new AnnotationBuilder('first'))->with('new_property', 'new value')->build()
        );
    }

    public function testWithoutProperty()
    {
        $this->assertEquals(
            [
                'id' => 1,
                'content' => 'First content',
            ],
            (new AnnotationBuilder('first'))->without('title')->build()
        );
    }

    public function testInexistentAlias()
    {
        $this->assertEquals(
            [],
            (new AnnotationBuilder('inexistent_alias'))->build()
        );
    }

}
<?php
declare(strict_types = 1);

/**
 * Class AbstractBuilder
 *
 * Build data for testing/development purposes, by Test Data Builder Pattern.
 * Inspired by http://geekswithblogs.net/Podwysocki/archive/2008/01/08/118362.aspx.
 *
 * Examples with StudentBuilder:
 *  - (new StudentBuilder('john'))->build()
 *      returns [
 *                'id' => 1,
 *                'first_name' => 'John',
 *                'last_name' => 'Doe',
 *                'e_mail' => 'john.doe@foo.com',
 *                'phone' => '1234-567890',
 *                'nationality' => 'GB',
 *              ]
 *  - (new StudentBuilder('john'))->with('nationality', 'IT')->build()
 *      returns [
 *                'id' => 1,
 *                'first_name' => 'John',
 *                'last_name' => 'Doe',
 *                'e_mail' => 'john.doe@foo.com',
 *                'phone' => '1234-567890',
 *                'nationality' => 'IT', -----------------------> CHANGED PROPERTY
 *              ]
 *  - (new StudentBuilder('john'))->with('new_property', 'new value')->build()
 *      returns [
 *                'id' => 1,
 *                'first_name' => 'John',
 *                'last_name' => 'Doe',
 *                'e_mail' => 'john.doe@foo.com',
 *                'phone' => '1234-567890',
 *                'nationality' => 'GB',
 *                'new_property' => 'new value', ---------------> ADDED PROPERTY
 *              ]
 *  - (new StudentBuilder('john'))->without('phone')->build()
 *      returns [
 *                'id' => 1,
 *                'first_name' => 'John',
 *                'last_name' => 'Doe',
 *                'e_mail' => 'john.doe@foo.com',
 *                'nationality' => 'GB,
 *              ]
 *  - (new StudentBuilder('inexistent_alias'))->build()
 *      returns []
 */
abstract class AbstractBuilder
{

    /**
     * @var array starting data, optionally to be customized, identified by alias
     */
    protected $baseData = [];

    /**
     * @var array data to be returned with build() execution
     */
    protected $builtData = [];

    /**
     * @var array property aliases to be used in case of property name change
     */
    protected $propertyAliases = [
        // 'old_name_used_by_tests' => 'new_name'
    ];

    /**
     * AbstractBuilder constructor.
     *
     * @param string $alias base data identifier
     */
    public function __construct(string $alias)
    {
        $alias = strtolower($alias);
        if (isset($this->baseData[$alias]) === true) {
            $this->builtData = $this->baseData[$alias];
        }
        return $this;
    }

    /**
     * Add a property to data being built.
     *
     * @param string $property
     * @param $value
     *
     * @return AbstractBuilder
     */
    public function with(string $property, $value) : self
    {
        if (array_key_exists($property, $this->propertyAliases) === true) {
            $property = $this->propertyAliases[$property];
        }
        $this->builtData[$property] = $value;
        return $this;
    }

    /**
     * Remove a property from data being built.
     *
     * @param string $property
     *
     * @return AbstractBuilder
     */
    public function without(string $property) : self
    {
        if (array_key_exists($property, $this->propertyAliases) === true) {
            $property = $this->propertyAliases[$property];
        }
        if (array_key_exists($property, $this->builtData) === true) {
            unset($this->builtData[$property]);
        }
        return $this;
    }

    /**
     * Return data being built.
     *
     * @return array
     */
    public function build() : array
    {
        return $this->builtData;
    }

}
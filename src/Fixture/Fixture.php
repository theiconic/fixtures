<?php

namespace TheIconic\Fixtures\Fixture;

use IteratorAggregate;
use Countable;

/**
 * Class Fixture
 * @package TheIconic\Fixtures\Fixture
 */
class Fixture implements IteratorAggregate, Countable
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $data;

    /**
     * @param $name
     * @param array $data
     */
    private function __construct($name, array $data)
    {
        $this->name = $name;
        $this->data = $data;
    }

    /**
     * Creates a new fixture.
     *
     * @param array $fixtureArray
     * @return Fixture
     */
    public static function create(array $fixtureArray)
    {
        $name = key($fixtureArray);
        $data = $fixtureArray[$name];

        $fixture = new self($name, $data);

        return $fixture;
    }

    /**
     * Replaces the fixture data.
     *
     * @param array $data
     * @return Fixture
     */
    public function setData(array $data) {
        $this->data = $data;

        return $this;
    }

    /**
     * Returns the iterable array.
     *
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->data);
    }

    /**
     * Returns the number of rows in the fixture.
     *
     * @return int
     */
    public function count()
    {
        return count($this->data);
    }

    /**
     * Gets the name of the fixture.
     *
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }
}

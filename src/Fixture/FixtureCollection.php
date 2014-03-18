<?php

namespace TheIconic\Fixtures\Fixture;

use TheIconic\Fixtures\Exception\FixtureException;

/**
 * Class FixtureCollection
 * @package TheIconic\Fixtures\Fixture
 */
class FixtureCollection implements \IteratorAggregate, \Countable
{
    /**
     * Contains the fixtures loaded.
     *
     * @var array
     */
    private $fixtures = [];

    /**
     * Creates a fixture collection.
     *
     * @param Fixture $fixture
     * @return FixtureCollection
     */
    public static function create(Fixture $fixture)
    {
        $fixtureCollection = new self();

        $fixtureCollection->add($fixture);

        return $fixtureCollection;
    }

    /**
     * Adds a fixture to the collection.
     *
     * @param Fixture $fixture
     * @return $this
     * @throws \TheIconic\Fixtures\Exception\FixtureException
     */
    public function add(Fixture $fixture)
    {
        if (isset($this->fixtures[$fixture->getName()])) {
            throw new FixtureException('Fixture' . $fixture->getName() . ' already defined');
        } else {
            $this->fixtures[$fixture->getName()] = $fixture;
        }

        return $this;
    }

    /**
     * Returns the iterable array.
     *
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->fixtures);
    }

    /**
     * Returns the number of fixtures in the collection.
     *
     * @return int
     */
    public function count()
    {
        return count($this->fixtures);
    }

}

<?php

namespace TheIconic\Fixtures\Test\Fixture;

use PHPUnit\Framework\TestCase;
use TheIconic\Fixtures\Fixture\Fixture;
use TheIconic\Fixtures\Fixture\FixtureCollection;

class FixtureCollectionTest extends TestCase
{

    private $testParsedDataCountry = [
        'country' => [
            ['id' => 1, 'name' => 'Australia'],
            ['id' => 2, 'name' => 'Venezuela']
        ]
    ];

    private $testParsedDataCities = [
        'cities' => [
            ['id' => 1, 'name' => 'Sydney'],
            ['id' => 2, 'name' => 'Maracaibo']
        ]
    ];

    private $testParsedDataFood = [
        'country' => [
            ['id' => 1, 'name' => 'Pizza'],
            ['id' => 2, 'name' => 'Sushi']
        ]
    ];

    /**
     * @var FixtureCollection
     */
    private $testFixtureCollection;

    public function setUp()
    {
        $fixtureCountry = Fixture::create($this->testParsedDataCountry);
        $this->testFixtureCollection = FixtureCollection::create($fixtureCountry);
    }

    public function testIterationAndAddAndCount()
    {
        foreach ($this->testFixtureCollection as $fixtureName => $fixtureInstance) {
            $this->assertEquals('country', $fixtureName);
            $this->assertInstanceOf('TheIconic\Fixtures\Fixture\Fixture', $fixtureInstance);
        }
        $this->assertCount(1, $this->testFixtureCollection);

        $fixtureCities = Fixture::create($this->testParsedDataCities);
        $this->testFixtureCollection->add($fixtureCities);

        $i = 0;
        foreach ($this->testFixtureCollection as $fixtureName => $fixtureInstance) {
            if ($i === 0) {
                $this->assertEquals('country', $fixtureName);
                $this->assertInstanceOf('TheIconic\Fixtures\Fixture\Fixture', $fixtureInstance);
            }

            if ($i === 1) {
                $this->assertEquals('cities', $fixtureName);
                $this->assertInstanceOf('TheIconic\Fixtures\Fixture\Fixture', $fixtureInstance);
            }

            $i++;
        }
        $this->assertCount(2, $this->testFixtureCollection);
    }

    /**
     * @expectedException \TheIconic\Fixtures\Exception\FixtureException
     */
    public function testIllegalAdd()
    {
        $fixtureCities = Fixture::create($this->testParsedDataCities);
        $this->testFixtureCollection->add($fixtureCities);

        $fixtureCities = Fixture::create($this->testParsedDataFood);
        $this->testFixtureCollection->add($fixtureCities);
    }
}

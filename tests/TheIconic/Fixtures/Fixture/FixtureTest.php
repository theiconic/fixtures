<?php

namespace TheIconic\Fixtures\Fixture;

use TheIconic\Fixtures\FixtureManager\FixtureManager;

class FixtureTest extends \PHPUnit_Framework_TestCase
{
    public function testFixture()
    {
        $fixtureManager = new FixtureManager();

        $fixtureManager->setSource('./tests/Support/TestsFixtures/country.yml')->parseYaml();

        //var_dump($fixtureManager->getData());

        print_r(get_declared_classes());
    }
}

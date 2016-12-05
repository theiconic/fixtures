<?php

namespace TheIconic\Fixtures\Parser;

use PHPUnit_Framework_TestCase;

class JsonParserTest extends PHPUnit_Framework_TestCase
{
    const TESTS_FIXTURES_DIRECTORY = './tests/Support/TestsFixtures/';

    /**
     * @var YamlParser
     */
    private $parserInstance;

    /**
     *
     */
    public function setUp()
    {
        $this->parserInstance = new JsonParser();
    }

    /**
     *
     */
    public function testParse()
    {
        $fixtureFile = self::TESTS_FIXTURES_DIRECTORY . 'country.json';

        $fixture = $this->parserInstance->parse($fixtureFile);

        $this->assertCount(247, $fixture);
        $this->assertEquals('country', $fixture->getName());
        $this->assertInstanceOf('TheIconic\Fixtures\Fixture\Fixture', $fixture);
    }
}

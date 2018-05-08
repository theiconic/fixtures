<?php

namespace TheIconic\Test\Fixtures\Parser;

use PHPUnit\Framework\TestCase;
use TheIconic\Fixtures\Parser\YamlParser;

class YamlParserTest extends TestCase
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
        $this->parserInstance = new YamlParser();
    }

    /**
     *
     */
    public function testParse()
    {
        $fixtureFile = self::TESTS_FIXTURES_DIRECTORY . 'country.yml';

        $fixture = $this->parserInstance->parse($fixtureFile);

        $this->assertCount(247, $fixture);
        $this->assertEquals('country', $fixture->getName());
        $this->assertInstanceOf('TheIconic\Fixtures\Fixture\Fixture', $fixture);
    }
}

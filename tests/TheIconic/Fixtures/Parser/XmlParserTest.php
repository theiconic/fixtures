<?php

namespace TheIconic\Fixtures\Parser;

use PHPUnit_Framework_TestCase;

class XmlParserTest extends PHPUnit_Framework_TestCase
{
    const TESTS_FIXTURES_DIRECTORY = './tests/Support/TestsFixtures/';

    /**
     * @var XMLParser
     */
    private $parserInstance;

    /**
     *
     */
    public function setUp()
    {
        $this->parserInstance = new XmlParser();
    }

    /**
     *
     */
    public function testParse()
    {
        $fixtureFile = self::TESTS_FIXTURES_DIRECTORY . 'customer_address_region_suburb.xml';

        $fixture = $this->parserInstance->parse($fixtureFile);

        $this->assertCount(16644, $fixture);
        $this->assertEquals('customer_address_region_suburb', $fixture->getName());
        $this->assertInstanceOf('TheIconic\Fixtures\Fixture\Fixture', $fixture);
    }

    /**
     * @expectedException \TheIconic\Fixtures\Exception\InvalidParserException
     */
    public function testParseException()
    {
        $fixtureFile = self::TESTS_FIXTURES_DIRECTORY . 'currency_conversion_damage.xml';

        $this->parserInstance->parse($fixtureFile);
    }
}

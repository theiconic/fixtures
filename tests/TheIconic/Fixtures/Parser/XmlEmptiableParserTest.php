<?php

namespace TheIconic\Fixtures\Parser;

class XmlEmptiableParserTest extends \PHPUnit_Framework_TestCase
{
    const TESTS_FIXTURES_DIRECTORY = './tests/Support/TestsFixtures/';

    /**
     * @var XMLEmptiableParser
     */
    private $parserInstance;

    public function setUp()
    {
        $this->parserInstance = new XmlEmptiableParser();
    }

    public function testXmlEmptiableParserShouldReturnEmptyFixtureWhenEmptyXmlIsSpecified()
    {
        $fixtureFile = self::TESTS_FIXTURES_DIRECTORY . 'currency_conversion_no_rows.empty.xml';

        $fixture = $this->parserInstance->parse($fixtureFile);

        $this->assertCount(0, $fixture);
        $this->assertEquals('currency_conversion', $fixture->getName());
        $this->assertInstanceOf('TheIconic\Fixtures\Fixture\Fixture', $fixture);
    }

    public function testXmlEmpitableParserShouldReturnFixtureWithDataWhenNonEmptyXmlIsSpecified()
    {
        $fixtureFile = self::TESTS_FIXTURES_DIRECTORY . 'currency_conversion.empty.xml';

        $fixture = $this->parserInstance->parse($fixtureFile);

        $this->assertCount(33, $fixture);
        $this->assertEquals('currency_conversion', $fixture->getName());
        $this->assertInstanceOf('TheIconic\Fixtures\Fixture\Fixture', $fixture);
    }
}

<?php

namespace TheIconic\Test\Fixtures\Parser;

use PHPUnit\Framework\TestCase;
use TheIconic\Fixtures\Parser\MasterParser;

class MasterParserTest extends TestCase
{
    const MASTER_PARSER_FULLY_QUALIFIED_NAME = 'TheIconic\Fixtures\Parser\MasterParser';

    const PARSER_INTERFACE_FULLY_QUALIFIED_NAME = 'TheIconic\Fixtures\Parser\ParserInterface';

    const TESTS_FIXTURES_DIRECTORY = './tests/Support/TestsFixtures/';

    const CURRENT_NUMBER_OF_PARSERS = 3;

    /**
     * @var MasterParser
     */
    private $parserInstance;

    public function setUp()
    {
        $this->parserInstance = new MasterParser();
    }

    public function testYamlParseViaMaster()
    {
        $fixtureFile = self::TESTS_FIXTURES_DIRECTORY . 'country_region.yml';

        $fixtureCountry = $this->parserInstance->parse($fixtureFile);

        $this->assertInstanceOf('TheIconic\Fixtures\Fixture\Fixture', $fixtureCountry);
    }

    public function testXmlParseViaMaster()
    {
        $fixtureFile = self::TESTS_FIXTURES_DIRECTORY . 'customer_address_region_suburb.xml';

        $fixtureAddress = $this->parserInstance->parse($fixtureFile);

        $this->assertInstanceOf('TheIconic\Fixtures\Fixture\Fixture', $fixtureAddress);
    }

    public function testGetAvailableParsers()
    {
        $availableParsers = $this->parserInstance->getAvailableParsers();

        $this->assertCount(self::CURRENT_NUMBER_OF_PARSERS, $availableParsers);

        foreach ($availableParsers as $parser) {
            $this->assertInstanceOf(self::PARSER_INTERFACE_FULLY_QUALIFIED_NAME, $parser);
        }
    }

    /**
     * @expectedException \TheIconic\Fixtures\Exception\InvalidParserException
     */
    public function testFakeInvalidParserException()
    {
        $parserInstance = $this->getMockBuilder(self::MASTER_PARSER_FULLY_QUALIFIED_NAME)
                               ->setMethods(['getParserNames'])
                               ->disableOriginalConstructor()
                               ->getMock();

        $parserInstance->expects($this->any())
            ->method('getParserNames')
            ->will($this->returnValue(['FakeParser']));

        $parserInstance->getAvailableParsers();
    }

    /**
     * @expectedException \TheIconic\Fixtures\Exception\InvalidParserException
     */
    public function testRougueInvalidParserException()
    {
        $parserInstance = $this->getMockBuilder(self::MASTER_PARSER_FULLY_QUALIFIED_NAME)
            ->setMethods(['getParserInstance'])
            ->getMock();

        $parserInstance->expects($this->any())
            ->method('getParserInstance')
            ->will($this->returnValue(null));

        $parserInstance->getAvailableParsers();
    }

    /**
     * @expectedException \TheIconic\Fixtures\Exception\ParserNotFoundException
     */
    public function testParserNotFoundException()
    {
        $this->parserInstance->parse(self::TESTS_FIXTURES_DIRECTORY . 'fake.txt');
    }
}

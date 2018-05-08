<?php

namespace TheIconic\Fixtures\Test\Replacer;

use PHPUnit\Framework\TestCase;
use TheIconic\Fixtures\Parser\XmlParser;
use TheIconic\Fixtures\Replacer\PlaceholderReplacer;

class PlaceholderReplacerTest extends TestCase
{
    const TESTS_FIXTURES_DIRECTORY = '/../../../../tests/Support/TestsFixtures/';

    /**
     * @var PlaceholderReplacer
     */
    private $replacerInstance;

    public function setUp()
    {
        $this->replacerInstance = new PlaceholderReplacer();
    }

    public function testPlaceholderReplacement()
    {
        $fixtureFile = __DIR__ . self::TESTS_FIXTURES_DIRECTORY . 'currency_conversion_placeholder.xml';

        $fixture = (new XmlParser())->parse($fixtureFile);

        $this->assertCount(4, $fixture);
        $this->assertEquals('currency_conversion_placeholder', $fixture->getName());

        $fixture = $this->replacerInstance->replaceValues(
            $fixture,
            [
                'currency_conversion_placeholder' => [
                    'fx:placeholder:jpy' => 777,
                    'fx:placeholder:gbp' => 888,
                ]
            ]
        );

        $i = 0;
        foreach ($fixture as $fixtureData) {
            if ($i === 0) {
                $this->assertEquals(1.288800, $fixtureData['rate']);
            } elseif ($i === 1) {
                $this->assertEquals(777, $fixtureData['rate']);
            } elseif ($i === 2) {
                $this->assertEquals(1.955800, $fixtureData['rate']);
            } elseif ($i === 3) {
                $this->assertEquals(888, $fixtureData['rate']);
            }

            $i++;
        }

        $this->assertInstanceOf('TheIconic\Fixtures\Fixture\Fixture', $fixture);
    }
}

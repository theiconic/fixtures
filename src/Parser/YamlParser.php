<?php

namespace TheIconic\Fixtures\Parser;

use TheIconic\Fixtures\Fixture\FixtureCollection;
use Symfony\Component\Yaml\Yaml;

/**
 * Class YamlParser
 * @package TheIconic\Fixtures\Parser
 */
class YamlParser extends AbstractParser
{
    /**
     * Yaml extension
     * @var string
     */
    protected static $parsableExtension = '.yml';

    /**
     * Parses a Yaml file.
     *
     * @param $source
     * @return FixtureCollection
     */
    public function parse($source)
    {
        $fixtureArray = Yaml::parse(file_get_contents($source));

        return FixtureCollection::create($fixtureArray);
    }
}

<?php

namespace TheIconic\Fixtures\Parser;

use TheIconic\Fixtures\Fixture\Fixture;
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
     * @return Fixture
     */
    public function parse($source)
    {
        $fixtureArray = Yaml::parse(file_get_contents($source));

        return Fixture::create($fixtureArray);
    }
}

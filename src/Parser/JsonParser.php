<?php

namespace TheIconic\Fixtures\Parser;

use TheIconic\Fixtures\Fixture\Fixture;

/**
 * Class JsonParser
 * @package TheIconic\Fixtures\Parser
 */
class JsonParser extends AbstractParser
{
    /**
     * Yaml extension
     * @var string
     */
    protected static $parsableExtension = '.json';

    /**
     * Parses a Yaml file.
     *
     * @param $source
     * @return Fixture
     */
    public function parse($source)
    {
        $fixtureArray = json_decode(file_get_contents($source), true);

        return Fixture::create($fixtureArray);
    }
}

<?php

namespace TheIconic\Fixtures\Parser;

use TheIconic\Fixtures\Fixture\FixtureCollection;
use Symfony\Component\Yaml\Yaml;

class YamlParser extends AbstractParser
{
    protected static $parsableExtension = '.yml';

    public function parse($source)
    {
        $fixtureArray = Yaml::parse(file_get_contents($source));

        return FixtureCollection::create($fixtureArray);
    }
}

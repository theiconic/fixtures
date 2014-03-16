<?php

namespace TheIconic\Fixtures\Parser;

use Symfony\Component\Yaml\Yaml;

class YamlParser extends AbstractParser
{
    protected  static $parsableExtension = '.yml';

    public function parse($source)
    {
        return Yaml::parse(file_get_contents($source));
    }
}

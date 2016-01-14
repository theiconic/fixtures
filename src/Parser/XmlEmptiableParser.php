<?php

namespace TheIconic\Fixtures\Parser;

use TheIconic\Fixtures\Exception\InvalidParserException;
use TheIconic\Fixtures\Fixture\Fixture;

/**
 * Class XmlParser
 * @package TheIconic\Fixtures\Parser
 */
class XmlEmptiableParser extends XmlParser
{
    /**
     * XML extension
     *
     * @var string
     */
    protected static $parsableExtension = '.empty.xml';

    /**
     * Parses a MySQL dump XML file.
     *
     * @param $source
     * @return Fixture
     */
    public function parse($source)
    {
        try {
            return parent::parse($source);
        } catch (InvalidParserException $e) {
            $z = new \XMLReader;
            $z->open($source);

            while ($z->read() && $z->name !== 'table_data');
            $tableName = $z->getAttribute('name');
            $fixtureArray[$tableName] = [];

            return Fixture::create($fixtureArray);
        }
    }
}

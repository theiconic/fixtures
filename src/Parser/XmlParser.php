<?php

namespace TheIconic\Fixtures\Parser;

use TheIconic\Fixtures\Fixture\FixtureCollection;
use TheIconic\Fixtures\Exception\InvalidParserException;

class XmlParser extends AbstractParser
{
    protected static $parsableExtension = '.xml';

    public function parse($source)
    {
        $data = [];
        $z = new \XMLReader;
        $z->open($source);

        $doc = new \DOMDocument;

        while ($z->read() && $z->name !== 'table_data');
        $tableName = $z->getAttribute('name');

        $rowNum = 0;
        while ($z->read() && $z->name !== 'row');
        while ($z->name === 'row')
        {
            $node = simplexml_import_dom($doc->importNode($z->expand(), true));

            $totalAttributes = $node->count();
            for ($i = 0; $i < $totalAttributes; $i++) {
                foreach ($node->field[$i]->attributes() as $attribute) {
                    $attribute = (string) $attribute;
                    $value = (string) $node->field[$i];

                    if (!empty($value)) {
                        $data[$tableName][$rowNum][$attribute] = $value;
                    }
                }
            }

            $rowNum++;
            $z->next('row');
        }

        if (empty($data)) {
            throw new InvalidParserException("It was not possible to parse the XML file: $source");
        }

        return FixtureCollection::create($data);
    }
}

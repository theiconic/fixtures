<?php

namespace TheIconic\Fixtures\Parser;


/**
 * Interface ParserInterface
 * @package TheIconic\Fixtures\Parser
 */
interface ParserInterface
{
    /**
     * Parses the source files with the right parser.
     *
     * @param $source
     * @return \TheIconic\Fixtures\Fixture\FixtureCollection
     */
    public function parse($source);

    /**
     * Indicates if a source file is parsable by this parser.
     *
     * @param $source
     * @return boolean
     */
    public function isParsable($source);
}

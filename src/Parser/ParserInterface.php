<?php

namespace TheIconic\Fixtures\Parser;


/**
 * Interface ParserInterface
 * @package TheIconic\Fixtures\Parser
 */
interface ParserInterface
{
    /**
     * Parses the source file with the right parser.
     *
     * @param $source
     * @return array
     */
    public function parse($source);

    /**
     * Indicates if a source file is parsable by the this parser.
     *
     * @param $source
     * @return boolean
     */
    public function isParsable($source);
}

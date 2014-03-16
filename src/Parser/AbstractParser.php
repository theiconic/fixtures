<?php

namespace TheIconic\Fixtures\Parser;

/**
 * Class AbstractParser
 * @package TheIconic\Fixtures\Parser
 */
abstract class AbstractParser implements ParserInterface
{
    /**
     * File extension parsable by this parser.
     *
     * @var string
     */
    protected static $parsableExtension = '';

    /**
     * {@inheritDoc}
     *
     * @param $source
     * @return bool
     */
    public function isParsable($source)
    {
        return strpos($source, static::$parsableExtension) !== false;
    }
}

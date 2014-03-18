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
        return !empty(static::$parsableExtension) && strpos($source, static::$parsableExtension) !== false;
    }
}

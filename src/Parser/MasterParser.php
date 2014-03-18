<?php

namespace TheIconic\Fixtures\Parser;

use TheIconic\Fixtures\Exception\ParserNotFoundException;
use TheIconic\Fixtures\Exception\InvalidParserException;
use Symfony\Component\Finder\Finder;

/**
 * Class MasterParser
 * @package TheIconic\Fixtures\Parser
 */
class MasterParser extends AbstractParser
{
    /**
     * Namespace to the parsers.
     */
    const THEICONIC_FIXTURES_PARSER_NAMESPACE = 'TheIconic\\Fixtures\\Parser\\';

    /**
     * Substring contained by Master parser class.
     */
    const MASTER_CLASS_SUBSTRING = 'Master';

    /**
     * Substring contained by parser interface.
     */
    const INTERFACE_SUBSTRING = 'Interface';

    /**
     * Substring contained by abstract parser.
     */
    const ABSTRACT_SUBSTRING = 'Abstract';

    /**
     * Extension por PHP files.
     */
    const PHP_EXTENSION = '.php';

    /**
     * Contains the available parsers.
     *
     * @var array
     */
    private $parsers;

    /**
     * On construction, sets the available parsers.
     *
     */
    public function __construct()
    {
        $this->parsers = $this->getAvailableParsers();
    }

    /**
     * {@inheritDoc}
     *
     * @param $source
     * @return \TheIconic\Fixtures\Fixture\FixtureCollection
     * @throws \TheIconic\Fixtures\Exception\ParserNotFoundException
     */
    public function parse($source)
    {
        /** @var ParserInterface $parser */
        foreach ($this->parsers as $parser) {
            if ($parser->isParsable($source)) {
                return $parser->parse($source);
            }
        }

        throw new ParserNotFoundException('No parser found for this file type: ' . $source);
    }

    /**
     * Returns an array with all the available fixture source file parsers.
     *
     * @return array
     * @throws \TheIconic\Fixtures\Exception\InvalidParserException
     */
    public function getAvailableParsers()
    {
        if ($this->parsers !== null) {
            $parsers = $this->parsers;
        } else {
            $parserNames = $this->getParserNames();

            foreach ($parserNames as $parserClass) {
                $parserClass = static::THEICONIC_FIXTURES_PARSER_NAMESPACE . $parserClass;

                $parserInstance = $this->getParserInstance($parserClass);

                if (!$parserInstance instanceof ParserInterface) {
                    throw new InvalidParserException(
                        'Invalid parser loaded, verify that all parsers implement ParserInterface'
                    );
                }

                $this->parsers[] = $parserInstance;
            }

            $parsers = $this->parsers;
        }

        return $parsers;
    }

    /**
     * Returns an instance of the parser class.
     *
     * @param $parserClass
     * @return ParserInterface
     * @throws \TheIconic\Fixtures\Exception\InvalidParserException
     */
    protected function getParserInstance($parserClass)
    {
        if (class_exists($parserClass)) {
            return new $parserClass();
        } else {
            throw new InvalidParserException('Invalid parser, class does not exist');
        }
    }

    /**
     * Gets the name for all the created parser classes in the parsers directory.
     * Filters the Master Parser and the Parser Interface.
     *
     * @return array
     */
    protected function getParserNames()
    {
        $parserNames = [];

        $finder = new Finder();

        // Filter for only valid parser files, not MasterParser, Abstract class or Interface
        $filter = function (\SplFileInfo $file) {
            if (strpos($file, static::MASTER_CLASS_SUBSTRING) !== false
                || strpos($file, static::INTERFACE_SUBSTRING) !== false
                || strpos($file, static::ABSTRACT_SUBSTRING) !== false
            ) {
                return false;
            }
        };

        $finder->files()->filter($filter)->in(__DIR__);

        foreach ($finder as $file) {
            $parserNames[] = str_replace(static::PHP_EXTENSION, '', $file->getRelativePathname());
        }

        return $parserNames;
    }
}

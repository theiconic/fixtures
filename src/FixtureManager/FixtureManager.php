<?php

namespace TheIconic\Fixtures\FixtureManager;

use TheIconic\Fixtures\Fixture\FixtureCollection;
use TheIconic\Fixtures\Parser\ParserInterface;
use TheIconic\Fixtures\Parser\MasterParser;
use TheIconic\Fixtures\Persister\PDOPersister;
use TheIconic\Fixtures\Persister\PersisterInterface;
use Symfony\Component\Filesystem\Filesystem;
use TheIconic\Fixtures\Exception\SourceNotFoundException;

/**
 * Class FixtureManager
 * @package TheIconic\Fixtures\FixtureManager
 */
class FixtureManager
{
    /**
     * @var ParserInterface
     */
    private $parser;

    /**
     * @var FixtureCollection
     */
    private $fixtureCollection;

    /**
     * @var PersisterInterface
     */
    private $persister;

    /**
     * On construction, initializes the fixture collection.
     *
     * @param $sources
     * @param ParserInterface $parser
     */
    private function __construct($sources, ParserInterface $parser)
    {
        $this->parser = $parser;
        $this->fixtureCollection = $this->parse($sources);
    }

    /**
     * Creates a new Fixture Manager.
     *
     * @param $sources
     * @return FixtureManager
     */
    public static function create($sources)
    {
        if (is_string($sources)) {
            $sources = [$sources];
        }

        $parser = new MasterParser();

        return new self($sources, $parser);
    }

    /**
     * Sets a parser for testing purpose.
     *
     * @param ParserInterface $parser
     * @return $this
     */
    public function setParser(ParserInterface $parser)
    {
        $this->parser = $parser;

        return $this;
    }

    /**
     * Gets the parser, initializes to master parser if null.
     *
     * @return ParserInterface
     */
    public function getParser()
    {
        if ($this->parser === null) {
            $this->setParser(new MasterParser());
        }

        return $this->parser;
    }

    /**
     * Sets a persister to storage.
     *
     * @param PersisterInterface $persister
     * @return $this
     */
    public function setPersister(PersisterInterface $persister)
    {
        $this->persister = $persister;

        return $this;
    }

    /**
     * Initializes and sets a PDO persister.
     *
     * @param $host
     * @param $database
     * @param $username
     * @param $password
     * @return $this
     */
    public function setPDOPersister($host, $database, $username, $password)
    {
        $this->setPersister(new PDOPersister($host, $database, $username, $password));

        return $this;
    }

    /**
     * Parses the source files to create the fixtures.
     * Initializes a the fixture collection, adds fixtures in case there is already one set.
     *
     * @param array $sources
     * @return FixtureCollection
     * @throws \TheIconic\Fixtures\Exception\SourceNotFoundException
     */
    public function parse(array $sources)
    {
        $fs = new Filesystem();

        foreach ($sources as $source) {
            if (!$fs->exists($source)) {
                throw new SourceNotFoundException('Fixture source file not found: ' . $source);
            }
        }

        foreach ($sources as $source) {
            $fixture = $this->parser->parse($source);
            if ($this->fixtureCollection === null) {
                $this->fixtureCollection = FixtureCollection::create($fixture);
            } else {
                $this->fixtureCollection->add($fixture);
            }
        }

        return $this->fixtureCollection;
    }

    /**
     * Persists all loaded fixtures in the collection to storage.
     *
     * @return $this
     * @throws \Exception
     */
    public function persist()
    {
        if ($this->persister === null) {
            throw new \RuntimeException('No persister has been set');
        }

        foreach ($this->fixtureCollection as $fixture) {
            $this->persister->persist($fixture);
        }

        $this->persister->close();

        return $this;
    }
}


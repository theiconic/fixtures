<?php

namespace TheIconic\Fixtures\FixtureManager;

use TheIconic\Fixtures\Fixture\FixtureCollection;
use TheIconic\Fixtures\Parser\ParserInterface;
use TheIconic\Fixtures\Parser\MasterParser;
use TheIconic\Fixtures\Persister\PDO\PersisterFactory;
use TheIconic\Fixtures\Persister\PersisterInterface;
use Symfony\Component\Filesystem\Filesystem;
use TheIconic\Fixtures\Exception\SourceNotFoundException;
use TheIconic\Fixtures\Persister\Redis\RedisPersisterFactory;

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
     */
    private function __construct($sources)
    {
        $this->parse($sources);
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

        return new self($sources);
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
     * Initializes and sets the default PDO persister.
     *
     * @param $host
     * @param $database
     * @param $username
     * @param $password
     * @return $this
     */
    public function setDefaultPDOPersister($host, $database, $username, $password)
    {
        $this->setPersister(PersisterFactory::create($host, $database, $username, $password));

        return $this;
    }

    /**
     * Initializes and sets the default Redis persister.
     * @param $host
     * @param $port
     * @param $dbNumber
     * @param $namespace
     * @param string $namespaceSeparator
     * @param null $serializer
     * @return $this
     */
    public function setDefaultRedisPersister($host, $port, $dbNumber, $namespace, $namespaceSeparator = ':', $serializer = null)
    {
        $this->setPersister(RedisPersisterFactory::create($host, $port, $dbNumber, $namespace, $namespaceSeparator, $serializer));

        return $this;
    }

    /**
     * Gets the persister instance currently set.
     *
     * @return PersisterInterface
     * @throws \RuntimeException
     */
    private function getPersister()
    {
        if ($this->persister === null) {
            throw new \RuntimeException('No persister has been set');
        }

        return $this->persister;
    }

    /**
     * Parses the source files to create the fixtures.
     * Initializes a the fixture collection, adds fixtures in case there is already one set.
     *
     * @param array $sources
     * @return $this
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
            $fixture = $this->getParser()->parse($source);
            if ($this->fixtureCollection === null) {
                $this->fixtureCollection = FixtureCollection::create($fixture);
            } else {
                $this->fixtureCollection->add($fixture);
            }
        }

        return $this;
    }

    /**
     * Persists all loaded fixtures in the collection to storage.
     *
     * @return $this
     */
    public function persist()
    {
        foreach ($this->fixtureCollection as $fixture) {
            $this->getPersister()->persist($fixture);
        }

        $this->getPersister()->close();

        return $this;
    }

    /**
     * Completely cleans the persistence storage.
     *
     * @return $this
     */
    public function cleanStorage()
    {
        $this->getPersister()->cleanStorage();

        return $this;
    }
}


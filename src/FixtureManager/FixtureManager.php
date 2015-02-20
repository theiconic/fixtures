<?php

namespace TheIconic\Fixtures\FixtureManager;

use TheIconic\Fixtures\Fixture\FixtureCollection;
use TheIconic\Fixtures\Parser\ParserInterface;
use TheIconic\Fixtures\Parser\MasterParser;
use TheIconic\Fixtures\Persister\PDO\PersisterFactory;
use TheIconic\Fixtures\Persister\PersisterInterface;
use TheIconic\Fixtures\Persister\Redis\RedisPersister;
use TheIconic\Fixtures\Replacer\PlaceholderReplacer;
use TheIconic\Fixtures\Replacer\ReplacerInterface;
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
     * @var ReplacerInterface
     */
    private $replacer;

    /**
     * @var array
     */
    private $placeholderReplacements = [];

    /**
     * On construction, initializes the fixture collection.
     *
     * @param array $sources
     * @param array $placeholderReplacements
     */
    private function __construct($sources, array $placeholderReplacements)
    {
        $this->placeholderReplacements = $placeholderReplacements;
        $this->parse($sources);
    }

    /**
     * Creates a new Fixture Manager.
     *
     * @param string|array $sources
     * @param array $placeholderReplacements
     * @return FixtureManager
     */
    public static function create($sources, array $placeholderReplacements = [])
    {
        if (is_string($sources)) {
            $sources = [$sources];
        }

        return new self($sources, $placeholderReplacements);
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
     * Sets the replacer (for testing purpose).
     *
     * @param ReplacerInterface $replacer
     * @return $this
     */
    public function setReplacer(ReplacerInterface $replacer)
    {
        $this->replacer = $replacer;

        return $this;
    }

    /**
     * Gets the replacer, initializes to placeholder for now.
     *
     * @return ReplacerInterface
     */
    public function getReplacer()
    {
        if ($this->replacer === null) {
            $this->setReplacer(new PlaceholderReplacer());
        }

        return $this->replacer;
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
        $this->setPersister(new RedisPersister($host, $port, $dbNumber, $namespace, $namespaceSeparator, $serializer));

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

        if (empty($sources)) {
            throw new SourceNotFoundException('You must pass some fixtures files to parse!');
        }

        foreach ($sources as $source) {
            if (!$fs->exists($source)) {
                throw new SourceNotFoundException('Fixture source file not found: ' . $source);
            }
        }

        foreach ($sources as $source) {
            $fixture = $this->getParser()->parse($source);

            if (!empty($this->placeholderReplacements)) {
                $fixture = $this->getReplacer()->replaceValues($fixture, $this->placeholderReplacements);
            }

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


<?php

namespace TheIconic\Fixtures\Persister\Redis;

use TheIconic\Fixtures\Exception\PersisterException;
use TheIconic\Fixtures\Persister\PersisterInterface;
use Redis;

/**
 * Class RedisPersister
 * @package TheIconic\Fixtures\Persister
 */
abstract class AbstractRedisPersister implements PersisterInterface
{
    /**
     * @var Redis
     */
    protected $conn;

    /**
     * @var array
     */
    protected $config;

    /**
     * On construction, saves connection parameters.
     *
     * @param string $host
     * @param int $port
     * @param int $dbNumber
     * @param string $namespace
     * @param string $namespaceSeparator
     * @param string $serializer
     */
    public function __construct($host, $port, $dbNumber, $namespace, $namespaceSeparator = ':', $serializer = null, $driver = 'redis')
    {
        $this->config['host'] = $host;
        $this->config['port'] = $port;
        $this->config['dbNumber'] = $dbNumber;
        $this->config['namespace'] = $namespace;
        $this->config['namespaceSeparator'] = $namespaceSeparator;
        $this->config['serializer'] = $serializer;
        $this->config['driver'] = $driver;
    }

    /**
     * Returns the Redis connection to database.
     *
     * @throws PersisterException
     * @return Redis
     */
    abstract protected function getConnection();

    /**
     * {@inheritDoc}
     *
     * @return bool
     */
    public function close()
    {
        $this->conn = null;

        return true;
    }
}

<?php

namespace TheIconic\Fixtures\Persister\Redis;

use TheIconic\Fixtures\Exception\PersisterException;
use TheIconic\Fixtures\Fixture\Fixture;
use Redis;

/**
 * Class RedisPersister
 * @package TheIconic\Fixtures\Persister\Redis
 */
class RedisPersister extends AbstractRedisPersister
{
    /**
     * Name for base tables in MySQL, this is to differentiate from Views when truncating.
     */
    const MYSQL_TABLE_TYPE_BASE_TABLE = 'BASE TABLE';

    /**
     * Name for the MySQL driver.
     */
    const DRIVER_NAME_REDIS = 'redis';

    /**
     * On construction, create a new Redis Persister instance.
     * @param string $host
     * @param int $port
     * @param int $dbNumber
     * @param string $namespace
     * @param string $namespaceSeparator
     * @param string $serializer
     */
    public function __construct($host, $port, $dbNumber, $namespace, $namespaceSeparator = ':', $serializer = null)
    {
        parent::__construct($host, $port, $dbNumber, $namespace, $namespaceSeparator, $serializer);
    }

    /**
     * {@inheritDoc}
     *
     * @throws PersisterException
     * @return Redis
     */
    protected function getConnection()
    {
        if ($this->conn === null) {
            try {
                $redis = new Redis();
                $redis->connect($this->config['host'], $this->config['port']);
                $redis->select($this->config['dbNumber']);
                $redis->setOption(Redis::OPT_SERIALIZER, $this->getSerializer($this->config['serializer']));
                $redis->setOption(Redis::OPT_PREFIX, $this->config['namespace']);

                $this->conn = $redis;
            } catch (\Exception $e) {
                throw new PersisterException('Redis Exception: ' . $e->getMessage());
            }
        }

        return $this->conn;
    }

    /**
     * {@inheritDoc} Cleans database table before doing so.
     *
     * @param Fixture $fixture
     * @return bool
     */
    public function persist(Fixture $fixture)
    {
        $fixtureName = $fixture->getName();
        return $this->getConnection()->set($fixtureName, $fixture->getIterator()->getArrayCopy());
    }

    /**
     * {@inheritDoc}
     *
     * @note flushDB always returns true
     * @return bool
     */
    public function cleanStorage()
    {
        return $this->getConnection()->flushDB();
    }

    /**
     * Get Serializer Type
     * @param null $serializer
     * @return int
     */
    protected function getSerializer($serializer = null)
    {
        switch ($serializer)
        {
            case 'php':
                return (defined('Redis::SERIALIZER_PHP')) ? Redis::SERIALIZER_PHP : 1;
            case 'igbinary':
                return (defined('Redis::SERIALIZER_IGBINARY')) ? Redis::SERIALIZER_IGBINARY : 2;
            default:
                return (defined('Redis::SERIALIZER_NONE')) ? Redis::SERIALIZER_NONE : 0;
        }
    }
}

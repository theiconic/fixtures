<?php

namespace TheIconic\Fixtures\Persister\Redis;

use Symfony\Component\Yaml\Yaml;
use TheIconic\Fixtures\Exception\PersisterException;
use TheIconic\Fixtures\Fixture\Fixture;
use Redis;
use RedisException;

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
        parent::__construct($host, $port, $dbNumber, $namespace, $namespaceSeparator, $serializer, self::DRIVER_NAME_REDIS);
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

        try {
            $success = $this->getConnection()->set($fixtureName, $fixture->getIterator()->getArrayCopy());
        } catch (\RedisException $e) {
            throw new PersisterException("ERROR with fixture '$fixtureName': " . $e->getMessage());
        }

        return $success;
    }

    /**
     * {@inheritDoc}
     *
     * @return bool
     */
    public function cleanStorage()
    {
        try {
            $success = $this->getConnection()->flushDB();
        } catch (\RedisException $e) {
            throw new PersisterException("ERROR with flushDB: " . $e->getMessage());
        }

        return $success;
    }

    protected function getSerializer($serializer = null)
    {
        switch ($serializer)
        {
            case 'php':
                return Redis::SERIALIZER_PHP;
            case 'igbinary':
                return Redis::SERIALIZER_IGBINARY;
            default:
                return Redis::SERIALIZER_NONE;
        }
    }
}

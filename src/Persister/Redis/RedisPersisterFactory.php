<?php

namespace TheIconic\Fixtures\Persister\Redis;

use TheIconic\Fixtures\Exception\PersisterException;

/**
 * Class PersisterFactory
 * @package TheIconic\Fixtures\Persister\Redis
 */
class RedisPersisterFactory
{
    /**
     * Name for the default driver.
     */
    const DEFAULT_REDIS_PERSISTER_DRIVER = 'redis';

    /**
     * Factory method for Redis Persisters.
     *
     * @param $host
     * @param $port
     * @param $dbNumber
     * @param $namespace
     * @param string $namespaceSeparator
     * @param string $serializer
     * @param string $driver
     * @return \TheIconic\Fixtures\Persister\PersisterInterface
     * @throws \TheIconic\Fixtures\Exception\PersisterException
     */
    public static function create($host, $port, $dbNumber, $namespace, $namespaceSeparator = ':', $serializer = null, $driver = self::DEFAULT_REDIS_PERSISTER_DRIVER)
    {
        $redisPersisterClass = __NAMESPACE__ . '\\' .  ucfirst($driver) . 'Persister';

        if (!class_exists($redisPersisterClass)) {
            throw new PersisterException('Persister class ' . $redisPersisterClass . ' for this driver is not defined: '. $driver);
        }

        $redisPersister = new $redisPersisterClass($host, $port, $dbNumber, $namespace, $namespaceSeparator, $serializer);

        return $redisPersister;
    }
}

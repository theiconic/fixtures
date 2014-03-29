<?php

namespace TheIconic\Fixtures\Persister\PDO;

use TheIconic\Fixtures\Exception\PersisterException;

/**
 * Class PersisterFactory
 * @package TheIconic\Fixtures\Persister\PDO
 */
class PersisterFactory
{
    /**
     * Name for the default driver.
     */
    const DEFAULT_PDO_PERSISTER_DRIVER = 'mysql';

    /**
     * Factory method for PDO Persisters.
     *
     * @param $host
     * @param $database
     * @param $username
     * @param $password
     * @param string $driver
     * @return \TheIconic\Fixtures\Persister\PersisterInterface
     * @throws \TheIconic\Fixtures\Exception\PersisterException
     */
    public static function create($host, $database, $username, $password, $driver = self::DEFAULT_PDO_PERSISTER_DRIVER)
    {
        $pdoPersisterClass = __NAMESPACE__ . '\\' .  $driver . 'Persister';

        if (!class_exists($pdoPersisterClass)) {
            throw new PersisterException('Persister class for this driver is not defined: '. $driver);
        }

        $pdoPersister = new $pdoPersisterClass($host, $database, $username, $password);

        return $pdoPersister;
    }
}

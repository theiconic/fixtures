<?php

namespace TheIconic\Fixtures\Persister\PDO;

use TheIconic\Fixtures\Exception\PersisterException;
use TheIconic\Fixtures\Persister\PersisterInterface;
use PDO;

/**
 * Class PDOPersister
 * @package TheIconic\Fixtures\Persister
 */
abstract class AbstractPDOPersister implements PersisterInterface
{
    /**
     * @var PDO
     */
    protected $conn;

    /**
     * @var array
     */
    protected $config;

    /**
     * On construction, saves connection parameters.
     *
     * @param $host
     * @param $database
     * @param $username
     * @param $password
     * @param string $driver
     */
    public function __construct($host, $database, $username, $password, $driver)
    {
        $this->config['driver'] = $driver;
        $this->config['host'] = $host;
        $this->config['database'] = $database;
        $this->config['username'] = $username;
        $this->config['password'] = $password;
    }

    /**
     * Returns the PDO connection to database.
     *
     * @throws PersisterException
     * @return PDO
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

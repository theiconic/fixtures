<?php

namespace TheIconic\Fixtures\Persister\PDO;

use TheIconic\Fixtures\Exception\PersisterException;
use TheIconic\Fixtures\Persister\PersisterInterface;
use PDO;
use PDOException;

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
     * Returns the PDO connection to database.
     *
     * @throws PersisterException
     * @return PDO
     */
    protected function getConnection()
    {
        if ($this->conn === null) {
            try {
                $dsn = $this->config['driver']
                    . ':host=' . $this->config['host']
                    . ';dbname=' . $this->config['database'];

                $this->conn = new PDO($dsn, $this->config['username'], $this->config['password']);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                throw new PersisterException('PDO Exception: ' . $e->getMessage());
            }
        }

        return $this->conn;
    }

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

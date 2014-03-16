<?php

namespace TheIconic\Fixtures\Persister;

use TheIconic\Fixtures\Fixture\Fixture;
use PDO;
use PDOException;

/**
 * Class PDOPersister
 * @package TheIconic\Fixtures\Persister
 */
class PDOPersister implements PersisterInterface
{
    /**
     * @var PDO
     */
    private $conn;

    /**
     * @var array
     */
    private $config;

    /**
     * Returns the PDO connection to database.
     *
     * @return PDO
     */
    private function getConnection()
    {
        if ($this->conn === null) {
            try {
                $dsn = $this->config['driver']
                    . ':host=' . $this->config['host']
                    . ';dbname=' . $this->config['database'];

                $this->conn = new PDO($dsn, $this->config['username'], $this->config['password']);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                echo 'ERROR: ' . $e->getMessage();
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
    public function __construct($host, $database, $username, $password, $driver = 'mysql')
    {
        $this->config['driver'] = $driver;
        $this->config['host'] = $host;
        $this->config['database'] = $database;
        $this->config['username'] = $username;
        $this->config['password'] = $password;
    }

    /**
     * {@inheritDoc} Cleans database table before doing so.
     *
     * @param Fixture $fixture
     * @return bool
     */
    public function persist(Fixture $fixture)
    {
        $database = $this->config['database'];
        $table = $fixture->getName();

        $this->getConnection()->query("SET FOREIGN_KEY_CHECKS = 0;");

        $this->getConnection()->query("TRUNCATE `$database`.`$table`;");

        foreach ($fixture as $fixtureData) {
            $columns = array_keys($fixtureData);
            $columns = implode('`, `', $columns);

            $values = array_map('mysql_real_escape_string', $fixtureData);
            $values = implode("', '", $values);

            $sql = "INSERT INTO `$database`.`$table` (`$columns`) VALUES('$values');";
            $pdoStatement = $this->getConnection()->prepare($sql);
            $pdoStatement->execute();
        }

        $this->getConnection()->query("SET FOREIGN_KEY_CHECKS = 1;");

        return true;
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

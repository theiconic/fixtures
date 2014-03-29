<?php

namespace TheIconic\Fixtures\Persister\PDO;

use TheIconic\Fixtures\Fixture\Fixture;

/**
 * Class MySQLPersister
 * @package TheIconic\Fixtures\Persister\PDO
 */
class MySQLPersister extends AbstractPDOPersister
{
    /**
     * Name for base tables in MySQL, this is to differentiate from Views when truncating.
     */
    const MYSQL_TABLE_TYPE_BASE_TABLE = 'BASE TABLE';

    /**
     * Name for the MySQL driver.
     */
    const DRIVER_NAME_MYSQL = 'mysql';

    /**
     * On construction, create a new MySQL Persister instance.
     * @param $host
     * @param $database
     * @param $username
     * @param $password
     */
    public function __construct($host, $database, $username, $password)
    {
        parent::__construct($host, $database, $username, $password, self::DRIVER_NAME_MYSQL);
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
    public function cleanStorage()
    {
        $database = $this->config['database'];

        $this->getConnection()->query("SET FOREIGN_KEY_CHECKS = 0;");

        $query = $this->getConnection()->query("SHOW FULL TABLES FROM `$database`;");

        foreach ($query as $row) {
            list($tableName, $tableType) = $row;

            if ($tableType !== self::MYSQL_TABLE_TYPE_BASE_TABLE) {
                continue;
            }

            $this->getConnection()->query("TRUNCATE `$database`.`$tableName`;");
        }

        $this->getConnection()->query("SET FOREIGN_KEY_CHECKS = 1;");

        return true;
    }
}

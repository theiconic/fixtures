<?php

namespace TheIconic\Fixtures\Persister\PDO;

class PersisterFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $persister = PersisterFactory::create(
            $_ENV['pdo_host'],
            $_ENV['pdo_database'],
            $_ENV['pdo_username'],
            $_ENV['pdo_password'],
            'mysql'
        );

        $this->assertInstanceOf('TheIconic\Fixtures\Persister\PDO\MysqlPersister', $persister);
    }

    /**
     * @expectedException \TheIconic\Fixtures\Exception\PersisterException
     */
    public function testInvalidCreate()
    {
        PersisterFactory::create(
            $_ENV['pdo_host'],
            $_ENV['pdo_database'],
            $_ENV['pdo_username'],
            $_ENV['pdo_password'],
            'fakedb'
        );
    }
}

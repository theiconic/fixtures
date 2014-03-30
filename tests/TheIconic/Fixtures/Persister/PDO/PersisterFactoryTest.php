<?php

namespace TheIconic\Fixtures\Persister\PDO;

class PersisterFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $persister = PersisterFactory::create(
            $_ENV['host'],
            $_ENV['database'],
            $_ENV['username'],
            $_ENV['password'],
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
            $_ENV['host'],
            $_ENV['database'],
            $_ENV['username'],
            $_ENV['password'],
            'fakedb'
        );
    }
}

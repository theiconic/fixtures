<?php

namespace TheIconic\Fixtures\Persister\Redis;

class RedisPersisterFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $persister = RedisPersisterFactory::create(
            $_ENV['redis_host'],
            $_ENV['redis_port'],
            $_ENV['redis_db_number'],
            $_ENV['redis_namespace'],
            $_ENV['redis_namespace_separator'],
            $_ENV['redis_serializer'],
            'redis'
        );

        $this->assertInstanceOf('TheIconic\Fixtures\Persister\Redis\RedisPersister', $persister);
    }

    /**
     * @expectedException \TheIconic\Fixtures\Exception\PersisterException
     */
    public function testInvalidCreate()
    {
        RedisPersisterFactory::create(
            'fake',
            $_ENV['redis_port'],
            $_ENV['redis_db_number'],
            $_ENV['redis_namespace'],
            $_ENV['redis_namespace_separator'],
            $_ENV['redis_serializer'],
            'fake'
        );
    }
}

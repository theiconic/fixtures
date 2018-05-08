<?php

namespace TheIconic\Test\Fixtures\Persister\Redis;

use PHPUnit\Framework\TestCase;
use TheIconic\Fixtures\Fixture\Fixture;
use TheIconic\Fixtures\Persister\Redis\RedisPersister;

class RedisPersisterTest extends TestCase
{
    private $testParsedData = [
        'country' => [
            ['id_country' => 1, 'iso2_code' => 'AU', 'iso3_code' => 'AUS', 'name' => 'Australia'],
            ['id_country' => 2, 'iso2_code' => 'VE', 'iso3_code' => 'VEN', 'name' => 'Venezuela'],
        ]
    ];

    /**
     * @var Fixture
     */
    private $testFixture;

    public function setUp()
    {
        $this->testFixture = Fixture::create($this->testParsedData);
    }

    public function testPersist()
    {
        $persister = new RedisPersister(
            $_ENV['redis_host'],
            $_ENV['redis_port'],
            $_ENV['redis_db_number'],
            $_ENV['redis_namespace'],
            $_ENV['redis_namespace_separator'],
            $_ENV['redis_serializer']
        );

        $this->assertTrue($persister->persist($this->testFixture));
        $this->assertTrue($persister->close());
    }

    public function testPersistNullSerializer()
    {
        $persister = new RedisPersister(
            $_ENV['redis_host'],
            $_ENV['redis_port'],
            $_ENV['redis_db_number'],
            $_ENV['redis_namespace'],
            $_ENV['redis_namespace_separator'],
            null
        );

        $this->assertTrue($persister->persist($this->testFixture));
        $this->assertTrue($persister->close());
    }

    public function testPersistIgbinariesSerializer()
    {
        $persister = new RedisPersister(
            $_ENV['redis_host'],
            $_ENV['redis_port'],
            $_ENV['redis_db_number'],
            $_ENV['redis_namespace'],
            $_ENV['redis_namespace_separator'],
            'igbinary'
        );

        $this->assertTrue($persister->persist($this->testFixture));
        $this->assertTrue($persister->close());
    }

    public function testCleanStorage()
    {
        $persister = new RedisPersister(
            $_ENV['redis_host'],
            $_ENV['redis_port'],
            $_ENV['redis_db_number'],
            $_ENV['redis_namespace'],
            $_ENV['redis_namespace_separator'],
            $_ENV['redis_serializer']
        );

        $persister->persist($this->testFixture);
        $this->assertTrue($persister->cleanStorage());
        $this->assertTrue($persister->close());
    }

    /**
     * @expectedException \TheIconic\Fixtures\Exception\PersisterException
     */
    public function testInvalidConnection()
    {
        $persister = new RedisPersister(
            'fake',
            $_ENV['redis_port'],
            $_ENV['redis_db_number'],
            $_ENV['redis_namespace'],
            $_ENV['redis_namespace_separator'],
            $_ENV['redis_serializer']
        );

        $persister->persist($this->testFixture);
        $this->assertTrue($persister->close());
    }
}

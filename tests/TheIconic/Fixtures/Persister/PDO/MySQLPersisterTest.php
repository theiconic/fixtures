<?php

namespace TheIconic\Fixtures\Persister\PDO;

use TheIconic\Fixtures\Fixture\Fixture;

class MysqlPersisterTest extends \PHPUnit_Framework_TestCase
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
        $persister = new MysqlPersister(
            $_ENV['host'],
            $_ENV['database'],
            $_ENV['username'],
            $_ENV['password']
        );

        $this->assertTrue($persister->persist($this->testFixture));
        $this->assertTrue($persister->close());
    }

    public function testCleanStorage()
    {
        $persister = new MysqlPersister(
            $_ENV['host'],
            $_ENV['database'],
            $_ENV['username'],
            $_ENV['password']
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
        $persister = new MysqlPersister(
            $_ENV['host'],
            $_ENV['database'],
            $_ENV['username'],
            'fake'
        );

        $persister->persist($this->testFixture);
        $this->assertTrue($persister->close());
    }
}

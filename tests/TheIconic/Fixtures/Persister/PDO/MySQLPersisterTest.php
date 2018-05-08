<?php

namespace TheIconic\Test\Fixtures\Persister\PDO;

use PHPUnit\Framework\TestCase;
use TheIconic\Fixtures\Fixture\Fixture;
use TheIconic\Fixtures\Persister\PDO\MysqlPersister;

class MysqlPersisterTest extends TestCase
{
    private $testParsedData = [
        'country' => [
            ['id_country' => 1, 'iso2_code' => 'AU', 'iso3_code' => 'AUS', 'name' => 'Australia'],
            ['id_country' => 2, 'iso2_code' => 'VE', 'iso3_code' => 'VEN', 'name' => 'Venezuela'],
        ]
    ];

    private $testParsedBadData = [
        'country' => [
            ['id_countri' => 1, 'iso2_code' => 'AU', 'iso3_code' => 'AUS', 'name' => 'Australia'],
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
            $_ENV['pdo_host'],
            $_ENV['pdo_database'],
            $_ENV['pdo_username'],
            $_ENV['pdo_password']
        );

        $this->assertTrue($persister->persist($this->testFixture));
        $this->assertTrue($persister->close());
    }

    /**
     * @expectedException \TheIconic\Fixtures\Exception\PersisterException
     */
    public function testInvalidPersist()
    {

        $persister = new MysqlPersister(
            $_ENV['pdo_host'],
            $_ENV['pdo_database'],
            $_ENV['pdo_username'],
            $_ENV['pdo_password']
        );

        $persister->persist(Fixture::create($this->testParsedBadData));
    }

    public function testCleanStorage()
    {
        $persister = new MysqlPersister(
            $_ENV['pdo_host'],
            $_ENV['pdo_database'],
            $_ENV['pdo_username'],
            $_ENV['pdo_password']
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
            $_ENV['pdo_host'],
            $_ENV['pdo_database'],
            $_ENV['pdo_username'],
            'fake'
        );

        $persister->persist($this->testFixture);
        $this->assertTrue($persister->close());
    }
}

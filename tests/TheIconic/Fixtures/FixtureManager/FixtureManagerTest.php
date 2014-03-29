<?php

namespace TheIconic\Fixtures\FixtureManager;

class FixtureManagerTest extends \PHPUnit_Framework_TestCase
{
    private $fixtures = [
        'customer_address_region_suburb.xml',
        'country_region.yml',
    ];

    private $conn;

    private function getConnection() {
        if ($this->conn === null) {
            $dsn = 'mysql'
                . ':host=' . $_ENV['host']
                . ';dbname=' . $_ENV['database'];

            $this->conn = new \PDO($dsn, $_ENV['username'], $_ENV['password']);
            $this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }

        return $this->conn;
    }

    public function tearDown()
    {
        $this->conn;
    }

    public function testFixtureManagerPersist()
    {
        $fixtures = array_map(function ($val) {
            return './tests/Support/TestsFixtures/' . $val;
        }, $this->fixtures);

        $fixtureManager = FixtureManager::create($fixtures);

        $fixtureManager
            ->setDefaultPDOPersister(
                $_ENV['host'],
                $_ENV['database'],
                $_ENV['username'],
                $_ENV['password']
            )
            ->persist();

        $rowsCountry = $this->getConnection()->query('SELECT count(1) FROM country_region;')->fetchColumn();
        $rowsSuburd = $this->getConnection()->query('SELECT count(1) FROM customer_address_region_suburb;')->fetchColumn();

        $this->assertEquals(8, $rowsCountry);
        $this->assertEquals(16644, $rowsSuburd);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testFixtureManagerIllegalPersist()
    {
        $fixtureManager = FixtureManager::create('./tests/Support/TestsFixtures/country_region.yml');

        $fixtureManager->persist();
    }

    /**
     * @expectedException \TheIconic\Fixtures\Exception\SourceNotFoundException
     */
    public function testFixtureManagerInvalidSource()
    {
        FixtureManager::create('fake.xml');
    }

    public function testFixtureManagerClean()
    {
        $fixtures = array_map(function ($val) {
            return './tests/Support/TestsFixtures/' . $val;
        }, $this->fixtures);

        $fixtureManager = FixtureManager::create($fixtures);

        $fixtureManager
            ->setDefaultPDOPersister(
                $_ENV['host'],
                $_ENV['database'],
                $_ENV['username'],
                $_ENV['password']
            )
            ->persist()
            ->cleanStorage();

        $rowsCountry = $this->getConnection()->query('SELECT count(1) FROM country_region;')->fetchColumn();
        $rowsSuburd = $this->getConnection()->query('SELECT count(1) FROM customer_address_region_suburb;')->fetchColumn();

        $this->assertEquals(0, $rowsCountry);
        $this->assertEquals(0, $rowsSuburd);
    }
}

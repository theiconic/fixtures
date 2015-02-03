<?php

namespace TheIconic\Fixtures\FixtureManager;

class FixtureManagerTest extends \PHPUnit_Framework_TestCase
{
    private $fixtures = [
        'customer_address_region_suburb.xml',
        'country_region.yml',
        'currency_conversion_placeholder.xml'
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
            return __DIR__ . '/../../../../tests/Support/TestsFixtures/' . $val;
        }, $this->fixtures);

        $fixtureManager = FixtureManager::create(
            $fixtures,
            [
                'currency_conversion_placeholder' => [
                    'fx:placeholder:jpy' => 777
                ]
            ]
        );

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
        $rowsCurrency = $this->getConnection()->query('SELECT count(1) FROM currency_conversion_placeholder;')->fetchColumn();
        $rowsCurrencyData = $this->getConnection()->query('SELECT * FROM currency_conversion_placeholder;')->fetchAll();

        $this->assertEquals(8, $rowsCountry);
        $this->assertEquals(16644, $rowsSuburd);
        $this->assertEquals(3, $rowsCurrency);

        $i = 0;
        foreach ($rowsCurrencyData as $data) {
            if ($i === 0) {
                $this->assertEquals(1.288800, $data['rate']);
            } elseif ($i === 1) {
                $this->assertEquals(777, $data['rate']);
            } elseif ($i === 2) {
                $this->assertEquals(1.955800, $data['rate']);
            }

            $i++;
        }
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

    /**
     * @expectedException \TheIconic\Fixtures\Exception\SourceNotFoundException
     */
    public function testFixtureManagerEmptySource()
    {
        FixtureManager::create([]);
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

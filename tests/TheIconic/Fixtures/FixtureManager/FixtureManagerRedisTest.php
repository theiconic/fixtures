<?php
namespace TheIconic\Fixtures\FixtureManager;
use Redis;

class FixtureManagerRedisTest extends \PHPUnit_Framework_TestCase
{
    private $fixtures = [
        'customer_address_region_suburb.xml',
        'country_region.yml',
    ];

    private $conn;

    private function getConnection() {
        if ($this->conn === null) {
            $redis = new Redis();
            $redis->connect($_ENV['redis_host'], $_ENV['redis_port']);
            $redis->select($_ENV['redis_db_number']);
            $redis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_PHP);
            $redis->setOption(Redis::OPT_PREFIX, $_ENV['redis_namespace']);

            $this->conn = $redis;
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
            ->setDefaultRedisPersister(
                $_ENV['redis_host'],
                $_ENV['redis_port'],
                $_ENV['redis_db_number'],
                $_ENV['redis_namespace'],
                $_ENV['redis_namespace_separator'],
                $_ENV['redis_serializer']
            )
            ->persist();

        $countryRegion = $this->getConnection()->get('country_region');
        $customerAddressRegionSuburb = $this->getConnection()->get('customer_address_region_suburb');

        $this->assertEquals(8, count($countryRegion));
        $this->assertEquals(16644, count($customerAddressRegionSuburb));
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
            ->setDefaultRedisPersister(
                $_ENV['redis_host'],
                $_ENV['redis_port'],
                $_ENV['redis_db_number'],
                $_ENV['redis_namespace'],
                $_ENV['redis_namespace_separator'],
                $_ENV['redis_serializer']
            )
            ->persist()
            ->cleanStorage();

        $countryRegion = $this->getConnection()->get('country_region');
        $customerAddressRegionSuburb = $this->getConnection()->get('customer_address_region_suburb');

        $this->assertFalse($countryRegion);
        $this->assertFalse($customerAddressRegionSuburb);
    }
}

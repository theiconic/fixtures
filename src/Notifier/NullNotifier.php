<?php


namespace TheIconic\Fixtures\Notifier;

use TheIconic\Fixtures\Fixture\Fixture;

/**
 * Class NullNotifier
 * @package TheIconic\Fixtures\Notifier
 */
class NullNotifier implements NotifierInterface
{
    /**
     * Notifies if an individual fixture succeeded or failed
     * 
     * @param Fixture $fixture
     * @param bool $success
     * 
     * @return void
     */
    public function notify(Fixture $fixture, $success)
    {
        
    }
}

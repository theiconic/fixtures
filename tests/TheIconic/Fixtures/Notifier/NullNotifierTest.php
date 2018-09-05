<?php

namespace TheIconic\Fixtures\Test\Replacer;

use PHPUnit\Framework\TestCase;
use TheIconic\Fixtures\Notifier\NullNotifier;

class NullNotifierTest extends TestCase
{
    /**
     * @var NullNotifier
     */
    private $notifierInstance;

    public function setUp()
    {
        $this->notifierInstance = new NullNotifier();
    }

    public function testImplementsNotifierInstance()
    {
        $this->assertInstanceOf('TheIconic\Fixtures\Notifier\NotifierInterface', $this->notifierInstance);
    }
}

<?php

namespace Internexus\Watcher\Tests;

use Internexus\Watcher\Config;
use Internexus\Watcher\Watcher;
use Internexus\Watcher\Entities\Segment;
use PHPUnit\Framework\TestCase;

final class WatcherTest extends TestCase
{
    public $watcher;

    public function setUp() : void
    {
        $config = new Config('MYT0K3N');
        $config->setEnabled(true);

        $this->watcher = new Watcher($config);
        $this->watcher->transaction('test');
    }

    public function testIsCapturing()
    {
        $this->assertTrue($this->watcher->isCapturing());
    }

    public function testStartSegment()
    {
        $this->assertInstanceOf(Segment::class, $this->watcher->segment('test_segment'));
    }
}

<?php

namespace Internexus\Watcher\Tests;

use Internexus\Watcher\Config;
use Internexus\Watcher\Watcher;
use PHPUnit\Framework\TestCase;

final class EntityTest extends TestCase
{
    public $watcher;

    public function setUp() : void
    {
        $config = new Config('MYT0K3N');
        $config->setEnabled(true);

        $this->watcher = new Watcher($config);
        $this->watcher->transaction('test');
    }

    public function testTransactionData()
    {
        $this->assertSame($this->watcher->current()->name, 'test');
        $this->assertSame($this->watcher->current()->model, $this->watcher->current()::MODEL_NAME);
        $this->assertSame($this->watcher->current()->type, $this->watcher->current()::TYPE_PROCESS);
    }

    public function testSegmentData()
    {
        $segment = $this->watcher->segment(__FUNCTION__, 'new segment.');

        $this->assertIsArray($segment->toArray());
        $this->assertSame($segment->model, $segment::MODEL_NAME);
        $this->assertSame($segment->type, __FUNCTION__);
        $this->assertSame($segment->label, 'new segment.');
        $this->assertSame($segment->transaction, $this->watcher->current()->only(['hash', 'timestamp']));
    }

    public function testEncoding()
    {
        $this->assertStringContainsString(trim(json_encode([
            'model' => 'transaction',
        ]), '{}'), json_encode($this->watcher->current()));

        $this->assertStringContainsString(trim(json_encode([
            'model' => 'segment',
            'type' => 'test',
        ]), '{}'), json_encode($this->watcher->segment('test')));

        $error = $this->watcher->reportException(new \DomainException('test error'));
        $this->assertStringContainsString(trim(json_encode([
            'model' => 'error'
        ]), '{}'), json_encode($error));
    }
}

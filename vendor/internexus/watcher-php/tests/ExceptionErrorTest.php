<?php

namespace Internexus\Watcher\Tests;

use Internexus\Watcher\Config;
use Internexus\Watcher\Watcher;
use Internexus\Watcher\Entities\Error;
use PHPUnit\Framework\TestCase;

final class ExceptionErrorTest extends TestCase
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
        $error = $this->watcher->reportException(
            new \DomainException('Generic error', 123)
        );

        $this->assertNotEmpty($error->transaction['hash']);
        $this->assertSame($error->transaction['name'], 'test');
    }

    public function testExceptionHandler()
    {
        $error = $this->watcher->reportException(
            new \DomainException('Generic error', 123)
        );

        $this->assertInstanceOf(Error::class, $error);
        $this->assertSame($error->file, __FILE__);
        $this->assertSame($error->class, 'DomainException');
        $this->assertSame($error->message, 'Generic error');
        $this->assertSame($error->code, 123);
        $this->assertNotEmpty($error->line);
        $this->assertNotEmpty($error->timestamp);
    }

    public function testStackTrace()
    {
        $e = new \DomainException('Generic error', 123);
        $error = $this->watcher->reportException($e);
        $stack = $e->getTrace();

        $this->assertTrue(is_array($error->stack));
        $this->assertSame($error->stack[0]['function'], $stack[0]['function']);
        $this->assertSame($error->stack[0]['class'], $stack[0]['class']);
    }
}

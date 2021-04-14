<?php

namespace Internexus\Watcher\Tests;

use Internexus\Watcher\Config;
use PHPUnit\Framework\TestCase;

final class ConfigTest extends TestCase
{
    public function testDefaults()
    {
        $config = new Config('MYT0K3N');

        $this->assertSame($config->getToken(), 'MYT0K3N');
        $this->assertSame($config->getUrl(), 'https://api.internexusweb.com.br/watcher/api/');
        $this->assertSame($config->isEnabled(), true);
        $this->assertSame($config->getMaxItems(), 100);
    }

    public function testFluentApi()
    {
        $config = new Config('MYT0K3N');

        $this->assertInstanceOf(Config::class, $config->setToken('MYT0K3N'));
        $this->assertSame($config->getToken(), 'MYT0K3N');

        $this->assertInstanceOf(Config::class, $config->setUrl('http://proxy.domain.com'));
        $this->assertSame($config->getUrl(), 'http://proxy.domain.com');

        $this->assertInstanceOf(Config::class, $config->setEnabled(false));
        $this->assertSame($config->isEnabled(), false);
    }
}

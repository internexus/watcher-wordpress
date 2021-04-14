<?php

namespace Internexus\Watcher\Collectors;

use Internexus\Watcher\Support\Arrayable;

class Url extends Arrayable
{
    public function __construct()
    {
        $this->protocol = isset($_SERVER['HTTPS']) ? 'https' : 'http';
        $this->port = $_SERVER['SERVER_PORT'] ?? '';
        $this->path = $_SERVER['SCRIPT_NAME'] ?? '';
        $this->search = '?' . (($_SERVER['QUERY_STRING'] ?? '') ?? '');
        $this->full = isset($_SERVER['HTTP_HOST']) ? $this->protocol . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] : '';
    }
}

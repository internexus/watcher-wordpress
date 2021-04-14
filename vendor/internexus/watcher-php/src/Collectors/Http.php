<?php

namespace Internexus\Watcher\Collectors;

use Internexus\Watcher\Support\Arrayable;

class Http extends Arrayable
{
    public function __construct()
    {
        $this->url = new Url();
        $this->request = new Request();
    }
}

<?php

namespace Internexus\Watcher\Collectors;

use Internexus\Watcher\Support\Arrayable;

class Request extends Arrayable
{
    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->version = substr($_SERVER['SERVER_PROTOCOL'], strpos($_SERVER['SERVER_PROTOCOL'], '/'));
        $this->socket = new Socket();
        $this->cookies = $_COOKIE;

        if (function_exists('apache_request_headers')) {
            $h = apache_request_headers();

            if (array_key_exists('sec-ch-ua', $h)) {
                unset($h['sec-ch-ua']);
            }

            $this->headers = $h;
        }
    }
}

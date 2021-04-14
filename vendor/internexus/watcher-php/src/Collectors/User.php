<?php

namespace Internexus\Watcher\Collectors;

use Internexus\Watcher\Support\Arrayable;

class User extends Arrayable
{
    /**
     * Constructor.
     *
     * @param  int          $id
     * @param  string|null  $name
     * @param  string|null  $email
     * @return void
     */
    public function __construct($id, $name = null, $email = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
    }
}

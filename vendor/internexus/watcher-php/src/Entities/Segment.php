<?php

namespace Internexus\Watcher\Entities;

class Segment extends Entity
{
    /**
     * Entity model name.
     *
     * @var string
     */
    const MODEL_NAME = 'segment';

    /**
     * Constructor.
     *
     * @param  string  $type
     * @param  string  $label
     * @param  \Internexus\Watcher\Entities\Transaction  $transaction
     */
    public function __construct($type, $label, Transaction $transaction)
    {
        $this->model = self::MODEL_NAME;
        $this->type = $type;
        $this->label = $label;
        $this->transaction = $transaction->only(['hash', 'timestamp']);
    }
}

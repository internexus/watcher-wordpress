<?php

namespace Internexus\Watcher;

use Internexus\Watcher\Entities\Transaction;
use Internexus\Watcher\Entities\Segment;
use Internexus\Watcher\Entities\Error;

class Watcher
{
    /**
     * Current transactions
     *
     * @var \Internexus\Watcher\Entities\Transaction
     */
    private $transaction;

    /**
     * Constructor.
     *
     * @param  \Internexus\Watcher\Config  $config
     * @return void
     */
    public function __construct(Config $config)
    {
        $this->http = new Http($config);
        $this->config = $config;

        register_shutdown_function([$this, 'flush']);
    }

    /**
     * Starts capturing a transaction.
     *
     * @param  string  $name
     * @return \Internexus\Watcher\Entities\Transaction
     */
    public function transaction($name)
    {
        $this->transaction = new Transaction($name);
        $this->transaction->start();

        $this->http->addEntity($this->transaction);

        return $this->transaction;
    }

    /**
     * Get current transaction.
     *
     * @return \Internexus\Watcher\Entities\Transaction
     */
    public function current()
    {
        return $this->transaction;
    }

    /**
     * Check if the transaction has already started.
     *
     * @return boolean
     */
    public function isCapturing()
    {
        return isset($this->transaction);
    }

    /**
     * Add a new segment to queue.
     *
     * @param  string  $type
     * @param  string  $label
     * @return \Internexus\Watcher\Entities\Segment
     */
    public function segment($type, $label = null)
    {
        $segment = new Segment($type, $label, $this->transaction);
        $segment->start();

        $this->http->addEntity($segment);

        return $segment;
    }

    /**
     * Report a exception error.
     *
     * @param  \Throwable  $exception
     * @return \Internexus\Watcher\Entities\Error
     */
    public function reportException(\Throwable $exception)
    {
        if (! $this->isCapturing()) {
            $this->transaction(get_class($exception));
        }

        $error = new Error($exception, $this->transaction);

        $this->http->addEntity($error);
        $this->transaction->setResult('error');

        $segment = $this->segment('exception', substr($exception->getMessage(), 0, 50));
        $segment->addContext('error', $error)->stop();

        return $error;
    }

    /**
     * Send data to platform.
     *
     * @return void
     */
    public function flush()
    {
        if (! $this->config->isEnabled() || ! $this->isCapturing()) {
            return;
        }

        if (! $this->current()->isFinished()) {
            $this->current()->end();
        }

        $this->http->send();
        unset($this->transaction);
    }
}

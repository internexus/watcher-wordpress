<?php

namespace Internexus\Watcher\Entities;

use Internexus\Watcher\Support\Arrayable;

abstract class Entity extends Arrayable
{
    /**
     * Start the timer.
     *
     * @param null|float $time
     * @return $this
     */
    public function start($time = null)
    {
        $this->timestamp = is_null($time) ? microtime(true) : $time;

        return $this;
    }

    /**
     * Stop the timer and calculate duration.
     *
     * @param null $duration
     * @return PerformanceModel
     */
    public function stop($duration = null)
    {
        $this->duration = $duration ?? round((microtime(true) - $this->timestamp)*1000, 2); // milliseconds

        return $this;
    }

    /**
     * Checkif timer was finished.
     *
     * @return boolean
     */
    public function isFinished()
    {
        return isset($this->duration) && $this->duration > 0;
    }

     /**
     * Add contextual information.
     *
     * @param  string $label
     * @param  mixed  $data
     * @return $this
     */
    public function addContext($label, $data)
    {
        $this->context[$label] = $data;

        return $this;
    }

    /**
     * Get context items.
     *
     * @param  string|null  $label
     * @return mixed
     */
    public function getContext($label = null)
    {
        if (is_string($label)) {
            return $this->context[$label] ?? null;
        }

        return $this->context;
    }
}

<?php

namespace Internexus\Watcher\Entities;

use Internexus\Watcher\Collectors\Host;
use Internexus\Watcher\Collectors\Http;
use Internexus\Watcher\Collectors\User;

class Transaction extends Entity
{
    /**
     * Entity model name.
     *
     * @var string
     */
    const MODEL_NAME = 'transaction';

    /**
     * Process type.
     *
     * @var string
     */
    const TYPE_PROCESS = 'process';

    /**
     * Request type.
     *
     * @var string
     */
    const TYPE_REQUEST = 'request';

    /**
     * Constructor.
     *
     * @param  string  $name
     * @return void
     */
    public function __construct($name)
    {
        $this->model = self::MODEL_NAME;
        $this->name = $name;
        $this->type = ! empty($_SERVER['REQUEST_METHOD']) ? self::TYPE_REQUEST : self::TYPE_PROCESS;
        $this->hash = $this->generateUniqueHash();
        $this->host = new Host();

        if ($this->type === self::TYPE_REQUEST) {
            $this->http = new Http;
        }
    }

    /**
     * Set a string representation of a transaction result (e.g. 'error', 'success', 'ok', '200', etc...).
     *
     * @param  string  $result
     * @return $this
     */
    public function setResult(string $result)
    {
        $this->result = $result;

        return $this;
    }

    /**
     * Attcach user information.
     *
     * @param  int          $id
     * @param  string|null  $name
     * @param  string|null  $email
     * @return void
     */
    public function withUser($id, $name = null, $email = null)
    {
        $this->user = new User($id, $name, $email);

        return $this;
    }

    /**
     * End the current transaction.
     *
     * @param  int  $duration
     * @return $this
     */
    public function end($duration = null)
    {
        // Sample memory peak at the end of execution.
        $this->memory_peak = $this->getMemoryPeak();

        return $this->stop($duration);
    }

    /**
     * Get transaction memory peak.
     *
     * @return float
     */
    public function getMemoryPeak()
    {
        return round((memory_get_peak_usage()/1024/1024), 2); // MB
    }

    /**
     * Generate a unique transaction hash.
     *
     * @param  int  $length
     * @return string
     * @throws \Exception
     */
    public function generateUniqueHash($length = 32)
    {
        if (!isset($length) || intval($length) <= 8) {
            $length = 32;
        }

        if (function_exists('random_bytes')) {
            return bin2hex(random_bytes($length));
        } elseif (function_exists('openssl_random_pseudo_bytes')) {
            return bin2hex(openssl_random_pseudo_bytes($length));
        }

        throw new \Exception('Can\'t create unique transaction hash.');
    }
}

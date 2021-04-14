<?php

namespace Internexus\Watcher;

class Config
{
    /**
     * API url.
     *
     * @var string
     */
    private $url = 'https://api.internexusweb.com.br/watcher/api/';

    /**
     * API token.
     *
     * @var string
     */
    private $token;

    /**
     * Package enabled.
     *
     * @var boolean
     */
    private $enabled = true;

    /**
     * Max numbers of items to collect.
     *
     * @var int
     */
    protected $maxItems = 100;

    /**
     * Constructor.
     *
     * @param  string  $url
     * @param  string  $token
     */
    public function __construct($token, $url = null)
    {
        $this->setToken($token);

        if (! empty( $url )) {
            $this->setUrl($url);
        }
    }

    /**
     * Set url.
     *
     * @param  string  $url
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function setUrl($url)
    {
        $value = trim($url);

        if (empty($value)) {
            throw new \InvalidArgumentException('Invalid URL');
        }

        $this->url = $url;

        return $this;
    }

    /**
     * Set token.
     *
     * @param  string  $token
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function setToken($token)
    {
        $value = trim($token);

        if (empty($value)) {
            throw new \InvalidArgumentException('Token cannot be empty');
        }

        $this->token = $token;

        return $this;
    }

    /**
     * Set package as enabled.
     *
     * @param  boolean  $enabled
     * @return $this
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Check if package is enable.
     *
     * @return boolean
     */
    public function isEnabled()
    {
        return ! empty( $this->token ) && $this->enabled;
    }

    /**
     * Get URL.
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Get Token.
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Get max number of items.
     *
     * @return int
     */
    public function getMaxItems()
    {
        return $this->maxItems;
    }

    /**
     * Max size of a POST request content.
     *
     * @return  integer
     */
    public function getMaxPostSize()
    {
        return OS::isWin() ? 8000 : 65536;
    }
}

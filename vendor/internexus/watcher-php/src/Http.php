<?php

namespace Internexus\Watcher;

use Internexus\Watcher\Entities\Entity;

class Http
{
    /**
     * CURL command path.
     *
     * @var string
     */
    protected $curlPath = 'curl';

    /**
     * Add an entry to queue.
     *
     * @var array
     */
    private $entities = [];

    /**
     * Constructor.
     *
     * @param  \Internexus\Watcher\Config  $config
     * @return void
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * Add entity.
     *
     * @param  \Internexus\Watcher\Entities\Entity
     * @return void
     */
    public function addEntity(Entity $entity)
    {
        if (count($this->entities) <= $this->config->getMaxItems()) {
            $this->entities[] = $entity;
        }
    }

    /**
     * Get request headers.
     *
     * @return array
     */
    protected function getHeaders()
    {
        return [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'X-Watcher-Key' => $this->config->getToken(),
        ];
    }

    /**
     * Send POST request.
     *
     * @return \GuzzleHttp\Promise\PromiseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function send($items = null)
    {
        $items = $items ?? $this->entities;
        $json = json_encode($items);
        $jsonLength = strlen($json);
        $count = count($items);

        if ($jsonLength > $this->config->getMaxPostSize()) {
            $chunkSize = floor($count / ceil($jsonLength / $this->config->getMaxPostSize()));
            $chunks = array_chunk($items, $chunkSize > 0 ? $chunkSize : 1);

            foreach ($chunks as $chunk) {
                $this->send($chunk);
            }
        } else {
            $this->sendChunk($items);
        }
    }

    /**
     * Send a portion of the load to the remote service.
     *
     * @param string $data
     * @return void
     */
    public function sendChunk($json)
    {
        $url = $this->config->getUrl() . 'ingest';
        $data = json_encode(['ingest' => $json]);
        $cmd = "{$this->curlPath} -X POST";

        foreach ($this->getHeaders() as $name => $value) {
            $cmd .= " --header \"$name: $value\"";
        }

        $cmd .= " --data {$this->getPayload($data)} {$url} --max-time 5";

        // Curl will run in the background
        if (OS::isWin()) {
            $cmd = "start /B {$cmd} > NUL";

            if (substr($data, 0, 1) === '@') {
                $cmd .= ' & timeout 1 > NUL & del /f ' . str_replace('@', '', $data);
            }
        } else {
            $cmd .= " > /dev/null 2>&1 &";
        }

        proc_close(proc_open($cmd, [], $pipes));
    }

    /**
     * Escape character to use in the CLI.
     *
     * Compatible to send data via file path: @../file/path.dat
     *
     * @param $string
     * @return mixed
     */
    protected function getPayload($string)
    {
        return OS::isWin()
            // https://stackoverflow.com/a/30224062/5161588
            ? '"' . str_replace('"', '""', $string) . '"'
            // http://stackoverflow.com/a/1250279/871861
            : "'" . str_replace("'", "'\"'\"'", $string) . "'";
    }
}

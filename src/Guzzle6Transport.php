<?php

namespace VaultTransports;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\TransferException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;
use Vault\Exceptions\TransportException;
use Vault\Transports\Transport;

/**
 * Class Guzzle6Transport
 *
 * @package Vault\Transports
 */
class Guzzle6Transport implements Transport
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * Guzzle6Transport constructor.
     *
     * @param array  $config
     * @param Client $client
     */
    public function __construct(array $config = [], Client $client = null)
    {
        // merge and override http errors settings
        $config = array_merge(
            [
                'base_uri' => 'http://127.0.0.1:8200',
                'timeout' => 15,
            ],
            $config,
            ['http_errors' => false]
        );

        $this->client = $client ?: new Client($config);
    }

    /**
     * @inheritdoc
     *
     * @throws \Vault\Exceptions\TransportException
     */
    public function sendAsync(RequestInterface $request, array $options = [])
    {
        try {
            return $this->client->sendAsync($request, $options);
        } catch (TransferException $e) {
            throw new TransportException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @inheritdoc
     *
     * @throws \Vault\Exceptions\TransportException
     */
    public function requestAsync($method, $uri = '', array $options = [])
    {
        try {
            return $this->client->requestAsync($method, $uri, $options);
        } catch (TransferException $e) {
            throw new TransportException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Send an HTTP request.
     *
     * @param RequestInterface $request Request to send
     * @param array            $options Request options to apply to the given
     *                                  request and to the transfer.
     *
     * @return ResponseInterface
     * @throws TransportException
     */
    public function send(RequestInterface $request, array $options = [])
    {
        try {
            return $this->client->send($request, $options);
        } catch (TransferException $e) {
            throw new TransportException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Create and send an HTTP request.
     *
     * Use an absolute path to override the base path of the client, or a
     * relative path to append to the base path of the client. The URL can
     * contain the query string as well.
     *
     * @param string              $method HTTP method.
     * @param string|UriInterface $uri URI object or string.
     * @param array               $options Request options to apply.
     *
     * @return ResponseInterface
     * @throws TransportException
     */
    public function request($method, $uri, array $options = [])
    {
        try {
            return $this->client->request($method, $uri, $options);
        } catch (TransferException $e) {
            throw new TransportException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Get a client configuration option.
     *
     * These options include default request options of the client, a "handler"
     * (if utilized by the concrete client), and a "base_uri" if utilized by
     * the concrete client.
     *
     * @param string|null $option The config option to retrieve.
     *
     * @return mixed
     */
    public function getConfig($option = null)
    {
        return $this->client->getConfig($option);
    }
}

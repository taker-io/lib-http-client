<?php

namespace TakerIo\HttpClient;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\TransferException;

/**
 * Class HTTP
 * @package App\Services
 */
class HTTP
{
    /**
     * @var string
     */
    protected $url;

    /**
     * @var array
     */
    protected $body = [];

    /**
     * @var array
     */
    protected $headers = [];

    /**
     * @var bool
     */
    protected $async = false;

    /**
     * @var bool
     */
    protected $assoc = false;

    /**
     * @var bool
     */
    protected $log = true;

    /**
     * @var string
     */
    protected $method;

    /**
     * @var Client
     */
    protected $client;
    protected $isJson = false;
    protected $options = [];
    protected $request;
    protected $response;

    /**
     * @var null|int
     */
    protected static $lastResponseStatusCode;

    /**
     * @var null|false|object|array
     */
    protected static $lastResponseData;

    /**
     * HTTP constructor.
     * @param string $url
     * @param bool $assoc
     * @param bool $log
     */
    public function __construct($url, $assoc = false, $log = true)
    {
        $this->url = $url;
        $this->assoc = $assoc;
        $this->log = $log;
        $this->client = new Client();
    }

    /**
     * @param string $url
     * @param bool $assoc
     * @param bool $log
     * @return HTTP
     */
    public static function init($url, $assoc = false, $log = true)
    {
        return new static($url, $assoc, $log);
    }

    /**
     * @param array $body
     * @return HTTP $this
     */
    public function body($body)
    {
        $this->body = $body;
        $this->options['body'] = $this->body;
        return $this;
    }

    /**
     * @param array $headers
     * @return HTTP $this
     */
    public function header(array $headers)
    {
        $filtered = [];
        foreach ($headers as $key => $value) {
            if (strpos($value, ': ') !== false) {
                list($a, $b) = explode(': ', $value);
                $filtered[$a] = $b;
            } else {
                $filtered[$key] = $value;
            }
        }
        $this->headers = array_merge($this->headers, $filtered);
        $this->options['headers'] = $this->headers;
        return $this;
    }

    /**
     * @param array $body
     * @return HTTP $this
     */
    public function json(array $body)
    {
        $this->isJson = true;
        $this->header(['Content-type' => 'application/json; charset=utf-8']);
        $this->body = $body;
        $this->options['json'] = $this->body;
        return $this;
    }

    /**
     * @return bool|mixed|string
     */
    public function get()
    {
        $this->method = 'GET';
        return $this->exec();
    }

    /**
     * @return bool|mixed|string
     */
    public function post()
    {
        $this->method = 'POST';
        return $this->exec();
    }

    /**
     * @return bool|mixed|string
     */
    public function put()
    {
        $this->method = 'PUT';
        return $this->exec();
    }

    /**
     * @return bool|mixed|string
     */
    public function delete()
    {
        $this->method = 'DELETE';
        return $this->exec();
    }

    /**
     * @return bool|mixed|string
     */
    protected function exec()
    {
        $this->_beforeRequest();
        $this->_request();
        return $this->_afterRequest();
    }

    protected function _beforeRequest()
    {

    }

    protected function _request()
    {
        try {
            $this->response = $this->client->request($this->method, $this->url, $this->options);
        } catch (TransferException $e) {
            $this->response = $e->getResponse();
        }
        static::$lastResponseStatusCode = $this->response ? $this->response->getStatusCode() : 0;
        $this->log();
    }

    protected function _afterRequest()
    {
        return $this->_formatResponse();
    }

    protected function _formatResponse()
    {
        $response = $this->response ? @json_decode($this->response->getBody(), $this->assoc) : null;
        if (!$response) {
            return false;
        }
        static::$lastResponseData = $response;
        return $response;
    }

    protected function log()
    {
        if ($this->log) {
            try {
                config('http-client.defaultLogModel')::create([
                    'route' => $this->url,
                    'type' => $this->method,
                    'request' => serialize($this->body),
                    'response' => $this->response ? serialize($this->response->getBody()->getContents()) : '',
                    'headers' => $this->response ? serialize($this->response->getHeaders()) : '',
                    'code' => static::$lastResponseStatusCode
                ]);
            } catch (\Exception $e) {
                if (function_exists('report')) {
                    report($e);
                }
            }
        }
    }

    /**
     * @return int|null
     */
    public static function getLastResponseStatusCode()
    {
        return static::$lastResponseStatusCode;
    }

    /**
     * @return array|false|object|null
     */
    public static function getLastResponseData()
    {
        return static::$lastResponseData;
    }
}

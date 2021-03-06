<?php

namespace App\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Promise;
use Predis\Client as Redis;

class MiddlewareService
{
    /**
     * An array containing all data for the middlewares in the json file
     *
     * @var array
     */
    private $data;

    /**
     * @var Client
     */
    private $client;

    /**
     * @var Redis
     */
    private $redis;

    /**
     * @param array $data
     * @param Client $client
     * @param Redis $redis
     */
    public function __construct($data, Client $client, Redis $redis)
    {
        $this->data = $data;
        $this->client = $client;
        $this->redis = $redis;
    }

    /**
     * @return mixed returns array if it finds the data, returns false
     *     on failure
     */
    public function getMiddlewares()
    {
        $requests = [];
        //setting values for requests array

        foreach ($this->data as $middleware) {
            // try to get the cached value
            $cached = $this->getFromCache($middleware->slug);
            if ($cached) {
                // cache hit, set the data on the data record
                $this->updateRecord($middleware->slug, $cached);
            } else {
                // cache miss, forward the request to packagist
                $requests[$middleware->slug] = $this->client->getAsync($middleware->packagistUrl);
            }
        }

        // Wait on all of the requests to complete. Throws a ConnectException if any of the requests fail
        try {
            $results = Promise\unwrap($requests);
        } catch (\Exception $e) {
            return false;
        }

        // Update and return middlewares with correct data
        foreach ($results as $key => $result) {
            $body = (string) $result->getBody();
            $this->updateRecord($key, $body);

            // put data into cache
            $this->updateCache($key, $body);
        }

        return $this->data;
    }

    /**
     * @param string $middlewareSlug name of the slug
     * @return mixed returns the middleware on success, false on failure
     */
    public function getMiddleware($middlewareSlug)
    {
        foreach ($this->data as $key => $middleware) {
            if ($middleware->slug === $middlewareSlug) {
                //send a GET request
                $response = $this->client->request('GET', $middleware->packagistUrl);

                //decode the JSON response
                $parsedResponse = json_decode($response->getBody());

                //update stars and DL values before returning
                $middleware->stars = $parsedResponse->package->github_stars;
                $middleware->downloads = $parsedResponse->package->downloads->total;

                // put data into cache
                $this->updateCache($key, json_encode($middleware));

                return $middleware;
            }
        }

        return false;
    }

    private function updateRecord($key, $raw)
    {
        $parsedResponse = json_decode($raw);
        $stars = $parsedResponse->package->github_stars;
        $downloads = $parsedResponse->package->downloads->total;

        $key = array_keys(array_filter($this->data, function ($record) use ($key) {
            return $record->slug === $key;
        }))[0];

        $this->data[$key]->stars = ((!isset($stars) || is_null($stars)) ? 0 : $stars);
        $this->data[$key]->downloads = ((!isset($downloads) || is_null($downloads)) ? 0 : $downloads);
    }

    private function getFromCache($key)
    {
        return $this->redis->get($key);
    }

    private function updateCache($key, $body)
    {
        // put data into cache
        $this->redis->set($key, $body);
        // cache expiration after 24h
        $this->redis->expire($key, 60 * 60 * 24);
    }
}
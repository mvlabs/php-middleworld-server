<?php

namespace App\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Promise;

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
     * @param string $data
     */
    public function __construct($data, Client $client)
    {
        $this->data = $data;
        $this->client = $client;
    }

    /**
     * @return mixed returns array if it finds the data, returns false
     *     on failure
     */
    public function getMiddlewares()
    {
        $requests = [];
        //setting values for requests array

        foreach ($this->data as $key => $middleware) {
            $requests[$key] = $this->client->getAsync($middleware->packagistUrl);
        }

        // Wait on all of the requests to complete. Throws a ConnectException if any of the requests fail
        try {
            $results = Promise\unwrap($requests);
        } catch (\Exception $e) {
            return false;
        }

        // Update and return middlewares with correct data
        foreach ($results as $key => $result) {
            $parsedResponse = json_decode($result->getBody());
            $this->data[$key]->stars = $parsedResponse->package->github_stars;
            $this->data[$key]->downloads = $parsedResponse->package->downloads->total;
        }

        return $this->data;
    }

    /**
     * @param string $middlewareSlug name of the slug
     * @return mixed returns the middleware on success, false on failure
     */
    public function getMiddleware($middlewareSlug)
    {
        foreach ($this->data as $middleware) {
            if ($middleware->slug === $middlewareSlug) {
                //send a GET request
                $response = $this->client->request('GET', $middleware->packagistUrl);

                //decode the JSON response
                $parsedResponse = json_decode($response->getBody());

                //update stars and DL values before returning
                $middleware->stars = $parsedResponse->package->github_stars;
                $middleware->downloads = $parsedResponse->package->downloads->total;

                return $middleware;
            }
        }

        return false;
    }
}

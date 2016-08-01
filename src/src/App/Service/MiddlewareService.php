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
     * @param string $data the path to the json file with the middleware data
     */
    public function __construct($data)
    {
        $this->data = json_decode(file_get_contents($data));
        $this->client = new Client();
    }

    /**
     * @return mixed returns array if it finds the data, returns false
     *     on failure
     */
    public function getMiddlewares()
    {
        $requests = [];
        //setting values for requests array

        for ($i=0; $i < sizeof($this->data); $i++) {
            $arr = explode ( '/' , $this->data[$i]->packagistUrl );
            $requests[$i] = $this->client->getAsync( 'https://packagist.org/packages/' . $arr[4] . "/" . $arr[5] . ".json" );
        }

        // Wait on all of the requests to complete. Throws a ConnectException if any of the requests fail
        // ASK HOW TO CORRECTLY MANAGE IT!
        try{
            $results = Promise\unwrap($requests);
        } catch(Exception $e) {
            return false;
        }

        $retData = $this->data; // is this right?

        // Update and return middlewares with correct data
        for ($j=0; $j < sizeof($results); $j++) {
            $parsedResponse = json_decode($results[$j]->getBody());
            $retData[$j]->stars = strval($parsedResponse->package->github_stars);
            $retData[$j]->downloads = strval($parsedResponse->package->downloads->total);
        }

        return array_values($retData);
    }

    /**
     * @return array
     */
    public function getMiddlewaresTest() //test with async non-concurrent requests
    {
        $retData = $this->data; // is this right?
        $parsedResponses = [];

        foreach ($retData as $mi) {

            $arr = explode ( '/' , $mi->packagistUrl );
            $promise = $this->client->getAsync('https://packagist.org/packages/' . $arr[4] . "/" . $arr[5] . ".json");
            $promise->then(
                function (ResponseInterface $res){
                    $parsedResponse = json_decode($res->getBody());
                    $mi->stars = strval($parsedResponse->package->github_stars);
                    $mi->downloads = strval($parsedResponse->package->downloads->total);
                    echo "done";
                },
                function (RequestException $e){
                    echo $e->getMessage() . "\n";
                }
            );
            $promise->wait();
        }
        return array_values($retData);
    }

    /**
     * @param string $middlewareSlug name of the slug
     * @return mixed returns the middleware on success, false on failure
     */
    public function getMiddleware($middlewareSlug)
    {
        foreach ($this->data as $middleware) {
            if ($middleware->slug === $middlewareSlug) {

                //find request parameters from packagistUrl
                $arr = explode ( '/' , $middleware->packagistUrl ); //4 author - 5 packageName

                //send a GET request
                $response = $this->client->request('GET', 'https://packagist.org/packages/' . $arr[4] . "/" . $arr[5] . ".json");

                //decode the JSON response
                $parsedResponse = json_decode($response->getBody());

                //update stars and DL values before returning
                $middleware->stars = strval($parsedResponse->package->github_stars);
                $middleware->downloads = strval($parsedResponse->package->downloads->total);

                return $middleware;
            }
        }

        return false;
    }

    /**
     * @param string $middlewareSlug
     * @return mixed returns the middleware on success, false on failure
     */
    public function getMiddlewareTest($middlewareSlug) //test with file_get_content
    {
        foreach ($this->data as $middleware) {
            if ($middleware->slug === $middlewareSlug) {

                //find request parameters from packagistUrl
                $arr = explode ( '/' , $middleware->packagistUrl ); //4 author - 5 packageName

                //send a GET request
                $response = file_get_contents("https://packagist.org/packages/" . $arr[4] . "/" . $arr[5] . ".json");

                //decode the JSON response
                $parsedResponse = json_decode($response);

                //update stars and DL values before returning
                $middleware->stars = strval($parsedResponse->package->github_stars);
                $middleware->downloads = strval($parsedResponse->package->downloads->total);

                return $middleware;
            }
        }

        return false;
    }
}

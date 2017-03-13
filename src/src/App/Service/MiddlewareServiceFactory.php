<?php

namespace App\Service;

use Interop\Container\ContainerInterface;
use GuzzleHttp\Client;

class MiddlewareServiceFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $path = $container->get('config')['middleware']['data'];
        $data = json_decode(file_get_contents($path));
        $redis = new \Predis\Client('tcp://redis:6379');

        $client = new Client();

        return new MiddlewareService($data, $client, $redis);
    }
}

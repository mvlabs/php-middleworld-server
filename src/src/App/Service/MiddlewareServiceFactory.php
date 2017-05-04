<?php

namespace App\Service;

use Interop\Container\ContainerInterface;
use Zend\Expressive\Router\RouterInterface;
use GuzzleHttp\Client;

class MiddlewareServiceFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $path = $container->get('config')['middleware']['data'];
        $data = json_decode(file_get_contents($path));
        // TODO make this configurable
        $redis = new \Predis\Client('tcp://phpmiddleworld-redis:6379');

        $client = new Client();

        return new MiddlewareService($data, $client, $redis);
    }
}

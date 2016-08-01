<?php

namespace App\Service;

use Interop\Container\ContainerInterface;
use Zend\Expressive\Router\RouterInterface;

class MiddlewareServiceFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $data = $container->get('config')['middleware']['data'];

        return new MiddlewareService($data);
    }
}

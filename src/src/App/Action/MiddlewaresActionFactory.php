<?php

namespace App\Action;

use Interop\Container\ContainerInterface;
use Zend\Expressive\Router\RouterInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

class MiddlewaresActionFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $router   = $container->get(RouterInterface::class);
        $middlewaresService = $container->get('App\Service\MiddlewareService');

        return new MiddlewaresAction($router, $middlewaresService);
    }
}

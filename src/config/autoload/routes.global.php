<?php

return [
    'dependencies' => [
        'invokables' => [
            Zend\Expressive\Router\RouterInterface::class => Zend\Expressive\Router\FastRouteRouter::class,
        ],
        'factories' => [
            App\Action\HomePageAction::class => App\Action\HomePageFactory::class,
            App\Action\MiddlewaresAction::class => App\Action\MiddlewaresActionFactory::class,
            App\Action\MiddlewareDetailsAction::class => App\Action\MiddlewareDetailsFactory::class,
        ],
    ],

    'routes' => [
        [
            'name' => 'home',
            'path' => '/',
            'middleware' => App\Action\HomePageAction::class,
            'allowed_methods' => ['GET'],
        ],
        [
            'name' => 'middlewares',
            'path' => '/v1/middlewares',
            'middleware' => App\Action\MiddlewaresAction::class,
            'allowed_methods' => ['GET'],
        ],
        [
            'name' => 'middleware-details',
            'path' => '/v1/middlewares/{middleware-slug:[a-z-]+}',
            'middleware' => App\Action\MiddlewareDetailsAction::class,
            'allowed_methods' => ['GET'],
        ],

    ],
];

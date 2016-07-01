<?php

namespace App\Action;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Expressive\Router;

class HomePageAction
{
    private $router;

    public function __construct(Router\RouterInterface $router)
    {
        $this->router = $router;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        $data = [];

        return new JsonResponse([
            'status' => 'OK'
            ]);

    }
}

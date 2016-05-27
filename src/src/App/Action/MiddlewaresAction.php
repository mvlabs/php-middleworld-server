<?php

namespace App\Action;

use App\Service\MiddlewareService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Expressive\Router;

class MiddlewaresAction
{
    /** @var Router\RouterInterface $router */
    private $router;
    /** @var MiddlewareService $middlewareService */
    private $middlewareService;

    /**
     * MiddlewaresAction constructor.
     * @param Router\RouterInterface $router
     * @param MiddlewareService $middlewareService
     */
    public function __construct(Router\RouterInterface $router, MiddlewareService $middlewareService)
    {
        $this->router = $router;
        $this->middlewareService = $middlewareService;
    }


    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        $data = $this->middlewareService->getMiddlewares();

        return new JsonResponse($data);

    }
}

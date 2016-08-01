<?php

namespace App\Action;

use App\Service\MiddlewareService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Expressive\Router\RouterInterface;

class MiddlewareDetailsAction
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var MiddlewareService
     */
    private $middlewareService;

    /**
     * @param RouterInterface $router
     * @param MiddlewareService $middlewareService
     */
    public function __construct(
        RouterInterface $router,
        MiddlewareService $middlewareService
    ) {
        $this->router = $router;
        $this->middlewareService = $middlewareService;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param callable|null $next
     * @return JsonResponse
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next = null
    ) {
        $slug = $request->getAttribute('middleware-slug');

        $middleware = $this->middlewareService->getMiddleware($slug);
        if ($middleware === false) {
            $resp = new JsonResponse([
                'message' => "No middleware found with slug: $slug"
            ]);
            $resp = $resp->withStatus(404);

            return $resp;
        }

        return new JsonResponse($middleware);
    }
}

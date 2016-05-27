<?php

namespace App\Action;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Expressive\Router;

class MiddlewaresAction
{
    private $router;

    public function __construct(Router\RouterInterface $router)
    {
        $this->router   = $router;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        $data = [];

        return new JsonResponse([
            [
                'author' => 'oscarotero',
                'slug' => 'access-log',
                'abstract' => 'Access Log Middleware',
                'url' => 'https://github.com/oscarotero/psr7-middlewares/blob/master/src/Middleware/AccessLog.php',
            ],
            [
                'author' => 'oscarotero',
                'slug' => 'cors',
                'abstract' => 'CORS Middleware',
                'url' => 'https://github.com/oscarotero/psr7-middlewares/blob/master/src/Middleware/Cors.php',
            ]
        ]);

    }
}

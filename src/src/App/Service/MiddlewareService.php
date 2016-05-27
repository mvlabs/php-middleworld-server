<?php

namespace App\Service;

class MiddlewareService
{
    private $data = [
        'access-log' => [
            'author' => 'oscarotero',
            'slug' => 'access-log',
            'abstract' => 'Access Log Middleware',
            'url' => 'https://github.com/oscarotero/psr7-middlewares/blob/master/src/Middleware/AccessLog.php',
        ],
        'cors' => [
            'author' => 'oscarotero',
            'slug' => 'cors',
            'abstract' => 'CORS Middleware',
            'url' => 'https://github.com/oscarotero/psr7-middlewares/blob/master/src/Middleware/Cors.php',
        ]
    ];

    /**
     * MiddlewareService constructor.
     */
    public function __construct()
    {
    }

    public function getMiddlewares()
    {
        return array_values($this->data);
    }

    public function getMiddleware($middlewareSlug)
    {
        if (array_key_exists($middlewareSlug, $this->data)) {
            return $this->data[$middlewareSlug];
        }
        return false;
    }
}

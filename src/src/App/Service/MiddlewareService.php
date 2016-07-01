<?php

namespace App\Service;

class MiddlewareService
{
    private $data;

    /**
     * MiddlewareService constructor.
     */
    public function __construct($data)
    {
        $this->data = json_decode(file_get_contents($data));
    }

    public function getMiddlewares()
    {
        return array_values($this->data);
    }

    public function getMiddleware($middlewareSlug)
    {
        foreach ($this->data as $middleware) {
            if ($middleware->slug === $middlewareSlug) {
                return $middleware;
            }
        }

        return false;
    }
}

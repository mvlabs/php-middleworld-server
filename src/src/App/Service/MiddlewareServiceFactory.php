<?php
/**
 * Created by PhpStorm.
 * User: whites
 * Date: 27/05/16
 * Time: 12.47
 */

namespace App\Service;

use Interop\Container\ContainerInterface;
use Zend\Expressive\Router\RouterInterface;

class MiddlewareServiceFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new MiddlewareService();
    }
}

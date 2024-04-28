<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Service\UrlpoolService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class UrlpoolMiddlewareFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): UrlpoolMiddleware
    {
        $middleware = new UrlpoolMiddleware();

        // historyService
        $middleware->setUrlpoolService($container->get(UrlpoolService::class));

        return $middleware;
    }
}

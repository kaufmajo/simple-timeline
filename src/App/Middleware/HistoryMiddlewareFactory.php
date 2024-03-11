<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Service\HistoryService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class HistoryMiddlewareFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): HistoryMiddleware
    {
        $middleware = new HistoryMiddleware();

        // historyService
        $middleware->setHistoryService($container->get(HistoryService::class));

        return $middleware;
    }
}

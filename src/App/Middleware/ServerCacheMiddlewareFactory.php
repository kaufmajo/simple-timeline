<?php

declare(strict_types=1);

namespace App\Middleware;

use Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class ServerCacheMiddlewareFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Exception
     */
    public function __invoke(ContainerInterface $container): ServerCacheMiddleware
    {
        $middleware = new ServerCacheMiddleware();

        // logger
        $middleware->setLogger($container->get('AppLogger'));

        // config
        $middleware->setConfig($container->get('config'));

        $cacheConfig = $middleware->getMyInitConfig('cache');

        if (isset($cacheConfig['server']) && isset($cacheConfig['server']['enabled'])) {
            if ($cacheConfig['server']['enabled']) {
                if (! isset($cacheConfig['server']['path'])) {
                    throw new Exception('The cache path is not configured');
                }
                if (! isset($cacheConfig['server']['lifetime'])) {
                    throw new Exception('The cache lifetime is not configured');
                }
            }
        }

        return $middleware;
    }
}

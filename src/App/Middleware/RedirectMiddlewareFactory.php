<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Container\ContainerInterface;

class RedirectMiddlewareFactory
{
    public function __invoke(ContainerInterface $container): RedirectMiddleware
    {
        return new RedirectMiddleware();
    }
}

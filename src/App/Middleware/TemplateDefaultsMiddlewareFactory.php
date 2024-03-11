<?php

declare(strict_types=1);

namespace App\Middleware;

use Mezzio\Template\TemplateRendererInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class TemplateDefaultsMiddlewareFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): TemplateDefaultsMiddleware
    {
        // renderer
        $renderer = $container->get(TemplateRendererInterface::class);

        // renderer
        return new TemplateDefaultsMiddleware($renderer);
    }
}

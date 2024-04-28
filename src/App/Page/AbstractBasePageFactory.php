<?php

declare(strict_types=1);

namespace App\Page;

use Mezzio\Template\TemplateRendererInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class AbstractBasePageFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function init(AbstractBasePage $handler, ContainerInterface $container): void
    {
        // config
        $handler->setConfig($container->get('config'));

        // logger
        $handler->setLogger($container->get('AppLogger'));

        // renderer
        $handler->setTemplateRenderer($container->get(TemplateRendererInterface::class));
    }
}

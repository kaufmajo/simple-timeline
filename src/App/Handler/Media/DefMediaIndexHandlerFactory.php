<?php

declare(strict_types=1);

namespace App\Handler\Media;

use App\Model\Media\MediaRepositoryInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class DefMediaIndexHandlerFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): DefMediaIndexHandler
    {
        $controller = new DefMediaIndexHandler();

        // config
        $controller->setConfig($container->get('config'));

        // repository
        $controller->setMediaRepository($container->get(MediaRepositoryInterface::class));

        return $controller;
    }
}

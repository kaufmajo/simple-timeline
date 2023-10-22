<?php

declare(strict_types=1);

namespace App\Plates\Extension;

use App\Model\Media\MediaRepositoryInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class MediaExtensionFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): MediaExtension
    {
        return new MediaExtension($container->get(MediaRepositoryInterface::class));
    }
}

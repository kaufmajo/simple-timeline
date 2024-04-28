<?php

declare(strict_types=1);

namespace App\Model\Media;

use App\Model\DbRunnerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class MediaCommandFactory implements FactoryInterface
{
    /**
     * @param string $requestedName
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): MediaCommand
    {
        // dbRunner
        $dbRunner = $container->get(DbRunnerInterface::class);

        // repository
        $mediaRepository = $container->get(MediaRepositoryInterface::class);

        // return instance
        $returnInstance = new MediaCommand($dbRunner, new MediaReflectionHydrator());
        $returnInstance->setMediaRepository($mediaRepository);

        return $returnInstance;
    }
}

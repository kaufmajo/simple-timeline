<?php

declare(strict_types=1);

namespace App\Model\Media;

use App\Model\DbRunnerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class MediaRepositoryFactory implements FactoryInterface
{
    /**
     * @param string $requestedName
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): MediaRepository
    {
        // dbRunner
        $dbRunner = $container->get(DbRunnerInterface::class);

        return new MediaRepository($dbRunner, new MediaReflectionHydrator(), new MediaEntity());
    }
}

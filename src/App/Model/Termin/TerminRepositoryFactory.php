<?php

declare(strict_types=1);

namespace App\Model\Termin;

use App\Model\DbRunnerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class TerminRepositoryFactory implements FactoryInterface
{
    /**
     * @param string $requestedName
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): TerminRepository
    {
        // dbRunner
        $dbRunner = $container->get(DbRunnerInterface::class);

        $repository = new TerminRepository($dbRunner, new TerminReflectionHydrator(), new TerminEntity());

        // config
        $repository->setConfig($container->get('config'));

        return $repository;
    }
}

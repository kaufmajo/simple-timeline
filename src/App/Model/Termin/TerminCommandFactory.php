<?php

declare(strict_types=1);

namespace App\Model\Termin;

use App\Model\DbRunnerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class TerminCommandFactory implements FactoryInterface
{
    /**
     * @param string $requestedName
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): TerminCommand
    {
        // dbRunner
        $dbRunner = $container->get(DbRunnerInterface::class);

        // repository
        $terminRepository = $container->get(TerminRepositoryInterface::class);

        // return instance
        $returnInstance = new TerminCommand($dbRunner, new TerminReflectionHydrator());
        $returnInstance->setTerminRepository($terminRepository);

        return $returnInstance;
    }
}

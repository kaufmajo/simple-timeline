<?php

declare(strict_types=1);

namespace App\Model;

use Laminas\Db\Adapter\AdapterInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class DbRunnerFactory implements FactoryInterface
{
    /**
     * @param string $requestedName
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): DbRunner
    {
        // db
        $db = $container->get(AdapterInterface::class);

        // logger
        $logger = $container->get('DbLogger');

        // return
        $dbRunner = new DbRunner($db);
        $dbRunner->setLogger($logger);

        return $dbRunner;
    }
}

<?php

declare(strict_types=1);

namespace App\Service;

use Laminas\Db\Adapter\AdapterInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class HistoryServiceFactory implements FactoryInterface
{
    /**
     * @param string $requestedName
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): HistoryService
    {
        // db
        $db = $container->get(AdapterInterface::class);

        return new HistoryService($db);
    }
}

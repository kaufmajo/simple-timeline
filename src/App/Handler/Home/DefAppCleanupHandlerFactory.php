<?php

declare(strict_types=1);

namespace App\Handler\Home;

use App\Handler\AbstractBaseHandlerFactory;
use Laminas\Db\Adapter\AdapterInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class DefAppCleanupHandlerFactory extends AbstractBaseHandlerFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): DefAppCleanupHandler
    {
        $handler = new DefAppCleanupHandler();

        // db adapter
        $handler->setDatabaseAdapter($container->get(AdapterInterface::class));

        parent::init($handler, $container);

        return $handler;
    }
}

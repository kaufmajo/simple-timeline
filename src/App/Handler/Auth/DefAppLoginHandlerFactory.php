<?php

declare(strict_types=1);

namespace App\Handler\Auth;

use App\Handler\AbstractBaseHandlerFactory;
use App\Service\HistoryService;
use Mezzio\Authentication\AuthenticationInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class DefAppLoginHandlerFactory extends AbstractBaseHandlerFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): DefAppLoginHandler
    {
        $handler = new DefAppLoginHandler($container->get(AuthenticationInterface::class));

        // historyService
        $handler->setHistoryService($container->get(HistoryService::class));

        parent::init($handler, $container);

        return $handler;
    }
}

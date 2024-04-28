<?php

declare(strict_types=1);

namespace App\Handler\Termin;

use App\Handler\AbstractBaseHandlerFactory;
use App\Model\Termin\TerminRepositoryInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class DefTerminCalendarHandlerFactory extends AbstractBaseHandlerFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): DefTerminCalendarHandler
    {
        $handler = new DefTerminCalendarHandler();

        // repository
        $handler->setTerminRepository($container->get(TerminRepositoryInterface::class));

        parent::init($handler, $container);

        return $handler;
    }
}

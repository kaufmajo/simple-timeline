<?php

declare(strict_types=1);

namespace App\Handler\Termin;

use App\Handler\AbstractBaseHandlerFactory;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class DefTerminHowtoHandlerFactory extends AbstractBaseHandlerFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): DefTerminHowtoHandler
    {
        $handler = new DefTerminHowtoHandler();

        parent::init($handler, $container);

        return $handler;
    }
}

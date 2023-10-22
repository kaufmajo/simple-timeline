<?php

declare(strict_types=1);

namespace App\Handler\Auth;

use Psr\Container\ContainerInterface;

class DefAppLogoutHandlerFactory
{
    public function __invoke(ContainerInterface $container): DefAppLogoutHandler
    {
        return new DefAppLogoutHandler();
    }
}

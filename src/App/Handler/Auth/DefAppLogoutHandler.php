<?php

declare(strict_types=1);

namespace App\Handler\Auth;

use Laminas\Diactoros\Response\RedirectResponse;
use Mezzio\Session\Session;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class DefAppLogoutHandler implements RequestHandlerInterface
{
    public function __construct()
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        /** @var Session $session */
        $session = $request->getAttribute('session');

        $session->clear();

        return new RedirectResponse('/');
    }
}

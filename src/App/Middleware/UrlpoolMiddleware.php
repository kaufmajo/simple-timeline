<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Service\HistoryService;
use App\Service\UrlpoolService;
use App\Traits\Aware\UrlpoolServiceAwareTrait;
use Mezzio\Session\RetrieveSession;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class UrlpoolMiddleware implements MiddlewareInterface
{
    private HistoryService $historyService;

    use UrlpoolServiceAwareTrait;

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $session = RetrieveSession::fromRequest($request);

        $this->getUrlpoolService()->setSession($session);

        return $handler->handle($request->withAttribute(UrlpoolService::class, $this->getUrlpoolService()));
    }
}

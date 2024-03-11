<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Service\HistoryService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

use const PHP_SAPI;

class HistoryMiddleware implements MiddlewareInterface
{
    private HistoryService $historyService;

    public function setHistoryService(HistoryService $historyService)
    {
        $this->historyService = $historyService;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ("cli" !== PHP_SAPI) {
            $this->historyService->insertRow();
        }

        return $handler->handle($request);
    }
}

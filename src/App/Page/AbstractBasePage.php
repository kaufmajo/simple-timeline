<?php

declare(strict_types=1);

namespace App\Page;

use App\Traits\Aware\ConfigAwareTrait;
use App\Traits\Aware\LoggerAwareTrait;
use App\Traits\Aware\TemplateRendererAwareTrait;
use Fig\Http\Message\StatusCodeInterface as StatusCode;
use Laminas\Diactoros\Response\EmptyResponse;
use Mezzio\Flash\FlashMessageMiddleware;
use Mezzio\Flash\FlashMessagesInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

use function method_exists;

abstract class AbstractBasePage implements RequestHandlerInterface
{
    use ConfigAwareTrait;

    use LoggerAwareTrait;

    use TemplateRendererAwareTrait;

    protected ?FlashMessagesInterface $flashMessages = null;

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $action = $request->getAttribute('action', 'index') . 'Action';

        if (!method_exists($this, $action)) {

            return new EmptyResponse(StatusCode::STATUS_NOT_FOUND);
        }

        return $this->$action($request);
    }

    public function flashMessages(ServerRequestInterface $request): FlashMessagesInterface
    {
        if (null === $this->flashMessages) {

            $this->flashMessages = $request->getAttribute(FlashMessageMiddleware::FLASH_ATTRIBUTE);
        }

        return $this->flashMessages;
    }
}
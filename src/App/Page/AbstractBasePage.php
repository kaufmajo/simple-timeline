<?php

declare(strict_types=1);

namespace App\Page;

use App\Service\UrlpoolService;
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
use ReflectionClassConstant;
use ReflectionException;

use function method_exists;

abstract class AbstractBasePage implements RequestHandlerInterface
{
    use ConfigAwareTrait;

    use LoggerAwareTrait;

    use TemplateRendererAwareTrait;

    protected ?FlashMessagesInterface $flashMessages = null;

    protected ServerRequestInterface $serverRequest;

    protected ?UrlpoolService $urlpoolService = null;

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $action = $request->getAttribute('action', 'index') . 'Action';

        if (! method_exists($this, $action)) {
            return new EmptyResponse(StatusCode::STATUS_NOT_FOUND);
        }

        $this->serverRequest = $request;

        $this->urlpoolService->setSession($this->serverRequest->getAttribute('session'));

        return $this->$action($request);
    }

    public function flashMessages(): FlashMessagesInterface
    {
        if (null === $this->flashMessages) {
            $this->flashMessages = $this->serverRequest->getAttribute(FlashMessageMiddleware::FLASH_ATTRIBUTE);
        }

        return $this->flashMessages;
    }

    public function setUrlPoolService(UrlpoolService $urlpoolService): void
    {
        $this->urlpoolService = $urlpoolService;
    }

    public function getUrlpoolService(): UrlpoolService
    {
        if (! $this->urlpoolService->getRootNamespace()) {
            try {
                $constantReflex = new ReflectionClassConstant(static::class, 'LAST_URL_NAMESPACE');

                $lastUrlNamespaceConstant = $constantReflex->getValue();
            } catch (ReflectionException) {
                $lastUrlNamespaceConstant = '';
            }

            $this->urlpoolService->setRootNamespace($lastUrlNamespaceConstant);
        }

        $url = $this->serverRequest->getUri()->getPath() . '?' . $this->serverRequest->getUri()->getQuery();

        $this->urlpoolService->url($url);

        return $this->urlpoolService;
    }
}

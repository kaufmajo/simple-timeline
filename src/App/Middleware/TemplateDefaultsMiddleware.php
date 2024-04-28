<?php

declare(strict_types=1);

namespace App\Middleware;

use Mezzio\Authentication\UserInterface;
use Mezzio\Flash\FlashMessageMiddleware;
use Mezzio\Flash\FlashMessagesInterface;
use Mezzio\Router\RouteResult;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class TemplateDefaultsMiddleware implements MiddlewareInterface
{
    private TemplateRendererInterface $templateRenderer;

    public function __construct(TemplateRendererInterface $templateRenderer)
    {
        $this->templateRenderer = $templateRenderer;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Inject the current user, or null if there isn't one.
        $this->templateRenderer->addDefaultParam(
            TemplateRendererInterface::TEMPLATE_ALL,
            'security', // This is named security so it will not interfere with your user admin pages
            $request->getAttribute(UserInterface::class)
        );

        // Inject the currently matched route name.
        $routeResult = $request->getAttribute(RouteResult::class);
        $this->templateRenderer->addDefaultParam(
            TemplateRendererInterface::TEMPLATE_ALL,
            'matchedRouteName',
            $routeResult ? $routeResult->getMatchedRouteName() : null
        );

        // Inject all flash messages
        /** @var FlashMessagesInterface $flashMessages */
        $flashMessages = $request->getAttribute(FlashMessageMiddleware::FLASH_ATTRIBUTE);
        $this->templateRenderer->addDefaultParam(
            TemplateRendererInterface::TEMPLATE_ALL,
            'notifications',
            $flashMessages ? $flashMessages->getFlashes() : []
        );

        // Inject any other data you always need in all your templates...

        return $handler->handle($request);
    }
}

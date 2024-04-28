<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RedirectMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
//        var_dump($request->getUri()->getHost());
//        var_dump($request->getUri()->getPath());
//        die('_____DEBUG_____');
//
//        if (
//            ('xxx.egli.church' === $request->getUri()->getHost() || 'xxx.development.net' === $request->getUri()->getHost())
//            && '/' === $request->getUri()->getPath()
//        )
//        {
//            //return new RedirectResponse('https://xxx.egli.church/xxx', 301);
//
//            $request = $request
//                ->withUri($request->getUri()->withPath('/xxx' .
//                    ('/' !== $request->getUri()->getPath() ? $request->getUri()->getPath() : '')));
//        }

        return $handler->handle($request);
    }
}

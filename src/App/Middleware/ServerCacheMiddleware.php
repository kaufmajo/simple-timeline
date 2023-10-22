<?php

/*
 *
 *  Hint: $response->getBody()->rewind(); // rewind can be required, since $response->getBody could be already called from earlier middleware
 *
 * When you press F5 in Chrome, it will always send requests to the server.
 * These will be made with the Cache-Control:max-age=0 header. The server will usually respond with a 304 (Not Changed) status code.
 * When you press Ctrl+F5 or Shift+F5, the same requests are performed, but with the Cache-Control:no-cache header,
 * thus forcing the server to send an uncached version, usually with a 200 (OK) status code.
 * If you want to make sure that you're utilizing the local browser cache, simply press Enter in the address bar.
 *
 * The browser will send a $_SERVER['HTTP_IF_MODIFIED_SINCE'] if it has a cached copy
 *
 */

declare(strict_types=1);

namespace App\Middleware;

use App\Service\HelperService;
use App\Traits\Aware\ConfigAwareTrait;
use App\Traits\Aware\LoggerAwareTrait;
use Laminas\Diactoros\Response\EmptyResponse;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\TextResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

use function array_merge;
use function file_exists;
use function file_get_contents;
use function file_put_contents;
use function filemtime;
use function json_decode;
use function json_encode;
use function md5;
use function str_replace;
use function strlen;
use function time;

class ServerCacheMiddleware implements MiddlewareInterface
{
    use ConfigAwareTrait;

    use LoggerAwareTrait;

    protected array $cacheConfig = [];

    public function getCacheFilename(ServerRequestInterface $request): string
    {
        // what is the cache filename
        $url = 1 < strlen($request->getUri()->getPath()) ? str_replace('/', '_', $request->getUri()->getPath()) : '_home';

        return $this->cacheConfig['server']['path'] . $url . '_' . md5($request->getUri()->getQuery()) . '.json';
    }

    public function getRemainingCacheTime(string $cacheFilename): int
    {
        return file_exists($cacheFilename) ? $this->cacheConfig['server']['lifetime'] - (time() - filemtime($cacheFilename)) : 0;
    }

    public function isCacheEnabled(ServerRequestInterface $request): bool
    {
        $cacheParam  = (bool) ($request->getQueryParams()['cache'] ?? true);
        $cacheConfig = (bool) $this->cacheConfig['server']['enabled'];

        return $cacheParam && $cacheConfig;
    }

    public function isBrowserCacheValid(string $ifModifiedSinceLine, int $remainingCacheTime): bool
    {
        return $ifModifiedSinceLine && 0 < $remainingCacheTime;
    }

    public function isServerCacheValid(int $remainingCacheTime): bool
    {
        return 0 < $remainingCacheTime;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->debug('Start a new cache request');

        // set config
        $this->cacheConfig = $this->getMyInitConfig('cache');

        // return early?
        if (! $this->isCacheEnabled($request)) {
            $this->debug('Cache is not Enabled');
            return $handler->handle($request);
        }

        // init
        $ifModifiedSinceLine = $request->getHeaderLine('if-modified-since');
        $cacheFilename       = $this->getCacheFilename($request);
        $remainingCacheTime  = $this->getRemainingCacheTime($cacheFilename);

        $this->debug('$ifModifiedSinceLine: ' . $ifModifiedSinceLine);
        $this->debug('$cacheFilename: ' . $cacheFilename);
        $this->debug('$remainingCacheTime: ' . $remainingCacheTime);

        // serve browser cache
        if ($this->isBrowserCacheValid($ifModifiedSinceLine, $remainingCacheTime)) {
            $this->debug('Browser cache is valid');
            return new EmptyResponse(304, ['Cache-Control' => 'private']);
        }

        // serve server cache
        if ($this->isServerCacheValid($remainingCacheTime)) {
            $this->debug('Server cache is valid');
            $headers = HelperService::getBrowserCacheHeaders($this->getRemainingCacheTime($cacheFilename));
            $cache   = json_decode(file_get_contents($cacheFilename), true);

            // send response to client
            return match ($cache['response']) {
                'text' => new TextResponse($cache['body'], 200, array_merge($cache['headers'], $headers)),
                default => new HtmlResponse($cache['body'], 200, array_merge($cache['headers'], $headers)),
            };
        }

        // if not cached - handle request
        $this->debug('There is no valid cache, so handle request');
        $response = $handler->handle($request);

        // create cached file
        if ($response instanceof HtmlResponse || $response instanceof TextResponse) {
            $this->debug('Create a new cache file');
            $cache = [
                'uri'      => $request->getUri()->getPath() . ($request->getUri()->getQuery() ? '?' . $request->getUri()->getQuery() : ''),
                'response' => match ($response::class) {
                    TextResponse::class => 'text',
                    default => 'html',
                },
                'headers'  => $response->getHeaders(),
                'body'     => $response->getBody()->getContents(),
            ];

            file_put_contents($cacheFilename, json_encode($cache));
        }

        return $response;
    }

    protected function debug(string $message): void
    {
        // set config
        $this->cacheConfig = $this->getMyInitConfig('cache');

        // process
        if ($this->cacheConfig['debug']) {
            $this->getLogger()->debug('ServerCacheMiddleware - ' . $message);
        }
    }
}

<?php

declare(strict_types=1);

namespace App\Handler\Media;

use App\Provider\MediaStorageProvider;
use App\Service\HelperService;
use App\Service\ThumbService;
use App\Traits\Aware\ConfigAwareTrait;
use App\Traits\Aware\MediaRepositoryAwareTrait;
use Exception;
use finfo;
use Laminas\Diactoros\Response;
use Laminas\Diactoros\Stream;
use Mezzio\Authentication\UserInterface;
use Mezzio\Session\SessionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

use function array_merge;
use function basename;

use const FILEINFO_MIME;

class DefMediaIndexHandler implements RequestHandlerInterface
{
    use ConfigAwareTrait;

    use MediaRepositoryAwareTrait;

    /**
     * @throws Exception
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        /** @var SessionInterface $session */
        $session = $request->getAttribute('session');
        $user    = $session->get(UserInterface::class);

        // param
        $mediaIdParam = (int) $request->getAttribute('p1');
        $widthParam   = (int) ($request->getQueryParams()['w'] ?? 0);
        $heightParam   = (int) ($request->getQueryParams()['h'] ?? null);

        // fetch the display media
        $mediaEntity = $this->getMediaRepository()->findMediaById($mediaIdParam);

        if ($reason = MediaStorageProvider::isMediaInvalid($mediaEntity, (bool) $user)) {
            return new Response\TextResponse($reason);
        }

        // media is valid, so send response to client

        // prepare headers
        $headers = [
            'Content-Type'        => (new finfo(FILEINFO_MIME))->file(MediaStorageProvider::getFilePath($mediaEntity)),
            'Content-Disposition' => 'inline; filename=' . basename($mediaEntity->getMediaName()),
        ];

        // media is an image
        if (MediaStorageProvider::isAnImage($mediaEntity)) {
            
            $cacheConfig = $this->getMyInitConfig('cache');

            // return thumb or original
            if (0 < $widthParam && 1200 >= $widthParam) {
                if (!MediaStorageProvider::isThumbInStorage($mediaEntity, $widthParam)) {
                    
                    ThumbService::createThumbnail(
                        MediaStorageProvider::getFilePath($mediaEntity),
                        MediaStorageProvider::getThumbFilePath($mediaEntity, $widthParam),
                        $widthParam, $heightParam
                    );
                }

                $stream = new Stream(MediaStorageProvider::getThumbFilePath($mediaEntity, $widthParam));
            } else {
                $stream = new Stream(MediaStorageProvider::getFilePath($mediaEntity));
            }

            return new Response($stream, 200, array_merge(
                $headers,
                HelperService::getBrowserCacheHeaders($cacheConfig['browser']['image_lifetime']),
                ['Content-Length' => "{$stream->getSize()}"],
            ));
        } else { // media is not an image
            $stream = new Stream(MediaStorageProvider::getFilePath($mediaEntity));

            return new Response($stream, 200, array_merge(
                $headers,
                [
                    'Pragma' => 'public', 
                    'Expires' => '0', 
                    'Cache-Control' => 'must-revalidate', 
                    'Content-Length' => "{$stream->getSize()}"
                ],
            ));
        }
    }
}

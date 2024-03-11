<?php

declare(strict_types=1);

namespace App\Plates\Extension;

use App\Model\Media\MediaRepositoryInterface;
use App\Provider\MediaStorageProvider;
use Exception;
use League\Plates\Engine;
use League\Plates\Extension\ExtensionInterface;
use League\Plates\Template\Template;

class MediaExtension implements ExtensionInterface
{
    protected Engine $engine;

    protected MediaRepositoryInterface $mediaRepository;

    public Template $template;

    public function __construct(MediaRepositoryInterface $mediaRepository)
    {
        $this->mediaRepository = $mediaRepository;
    }

    public function register(Engine $engine): void
    {
        $this->engine = $engine;

        $engine->registerFunction('media', [$this, 'media']);
    }

    public function media(?string $src = null): string
    {
        // init
        $dummy = '/img/image.png';
        $data  = $this->template->data();

        // return early?
        if (! $src) {
            return $dummy;
        }

        // process
        $mediaId = MediaStorageProvider::getMediaIdFromUrl($src);

        if ($mediaId) {
            
            $media = $this->mediaRepository->findMediaById($mediaId);

            try {
                if (MediaStorageProvider::isMediaInvalid($media, (bool) ($data['security'] ?? false))) {
                    return $dummy;
                }
            } catch (Exception) {
                return $dummy;
            }
        }

        return $src;
    }
}

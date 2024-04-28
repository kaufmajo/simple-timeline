<?php

declare(strict_types=1);

namespace App\Traits\Aware;

use App\Model\Media\MediaRepositoryInterface;

trait MediaRepositoryAwareTrait
{
    protected MediaRepositoryInterface $mediaRepository;

    /**
     * @return MediaRepositoryInterface
     */
    public function getMediaRepository()
    {
        return $this->mediaRepository;
    }

    public function setMediaRepository(MediaRepositoryInterface $mediaRepository)
    {
        $this->mediaRepository = $mediaRepository;
    }
}

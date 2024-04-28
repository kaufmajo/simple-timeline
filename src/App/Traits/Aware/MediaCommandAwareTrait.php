<?php

declare(strict_types=1);

namespace App\Traits\Aware;

use App\Model\Media\MediaCommandInterface;

trait MediaCommandAwareTrait
{
    protected MediaCommandInterface $mediaCommand;

    /**
     * @return MediaCommandInterface
     */
    public function getMediaCommand()
    {
        return $this->mediaCommand;
    }

    public function setMediaCommand(MediaCommandInterface $mediaCommand)
    {
        $this->mediaCommand = $mediaCommand;
    }
}

<?php

declare(strict_types=1);

namespace App\Traits\Entity;

use App\Enum\ReturnEnum;
use App\Model\Media\MediaEntityInterface;
use Exception;

trait MediaEntityTrait
{
    protected ?MediaEntityInterface $mediaEntity;

    /**
     * @throws Exception
     */
    public function getMediaEntityById(int $id, string $return = ReturnEnum::NOT_FOUND_EXCEPTION, ?MediaEntityInterface $protoType = null): MediaEntityInterface
    {
        if (ReturnEnum::NEW_ENTITY === $return && null === $protoType) {
            throw new Exception('A prototype is required.');
        }

        // init
        $mediaRepository = $this->mediaRepository;

        // process
        $this->mediaEntity = $mediaRepository->findMediaById($id);

        if ($this->mediaEntity) {
            return $this->mediaEntity;
        } elseif (ReturnEnum::NOT_FOUND_EXCEPTION === $return) {
            throw new Exception('Entity not found.');
        } elseif (ReturnEnum::NEW_ENTITY === $return) {
            return new $protoType();
        }

        return $this->mediaEntity;
    }
}

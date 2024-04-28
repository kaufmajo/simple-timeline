<?php

declare(strict_types=1);

namespace App\Model\Media;

use App\Model\AbstractReflectionHydrator;
use InvalidArgumentException;

class MediaReflectionHydrator extends AbstractReflectionHydrator
{
    public function hydrate(array $data, object $object): MediaEntity
    {
        if (! $object instanceof MediaEntity) {
            throw new InvalidArgumentException('$object must be an instance of Entity');
        }

        // hydrate from parent class

        /** @var MediaEntity $object */
        $object = parent::hydrate($data, $object);

        return $object;
    }

    public function extract(object $object): array
    {
        if (! $object instanceof MediaEntity) {
            throw new InvalidArgumentException('$object must be an instance of Entity');
        }

        // extract from parent class
        $data = parent::extract($object);

        // unset
        if (null === $data['media_groesse']) {
            unset($data['media_groesse']);
        }

        if (null === $data['media_mimetype']) {
            unset($data['media_mimetype']);
        }

        if (null === $data['media_name']) {
            unset($data['media_name']);
        }

        if (null === $data['media_hash']) {
            unset($data['media_hash']);
        }

        return $data;
    }
}

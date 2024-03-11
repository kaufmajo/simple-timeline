<?php

declare(strict_types=1);

namespace App\Storage;

use App\Model\Entity\EntityInterface;
use Exception;

class EntityStorage
{
    /** @var array */
    protected array $entityArray = [];

    /**
     * @param null $protoType
     */
    public function set(string $key, EntityInterface $entity, $protoType = null)
    {
        if ($entity instanceof EntityInterface) {
            $this->entityArray[$key] = $entity;
        } elseif ($protoType) {
            $this->entityArray[$key] = $protoType;
        }
    }

    /**
     * @return EntityInterface
     * @throws Exception
     */
    public function get(string $key)
    {
        if (isset($this->entityArray[$key])) {
            return $this->entityArray[$key];
        }

        throw new Exception('Entity is not in storage: ' . $key);
    }
}

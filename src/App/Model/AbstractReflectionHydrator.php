<?php

declare(strict_types=1);

namespace App\Model;

use Laminas\Hydrator\ReflectionHydrator;

abstract class AbstractReflectionHydrator extends ReflectionHydrator
{
    public function extract(object $object): array
    {
        $data = parent::extract($object);

        // unset fields
        unset($data['lastEntityAction']);

        return $data;
    }
}

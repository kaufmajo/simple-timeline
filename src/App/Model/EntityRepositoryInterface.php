<?php

declare(strict_types=1);

namespace App\Model;

interface EntityRepositoryInterface extends RepositoryInterface
{
    public function findEntityById(int $entityId);
}

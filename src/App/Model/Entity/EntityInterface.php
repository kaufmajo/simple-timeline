<?php

declare(strict_types=1);

namespace App\Model\Entity;

interface EntityInterface
{
    public function getEntityId(): ?int;

    public function setEntityId(int $entityId);

    public function getArrayCopy(): array;

    public function exchangeArray(array $data);

    public function getExchangeValue(array &$data, string $propertyName);

    public function setExchangeValue(array $data, string $key);

    public function getLastEntityAction();

    public function setLastEntityAction(string $action);
}

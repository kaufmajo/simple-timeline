<?php

declare(strict_types=1);

namespace App\Model\Entity;

use ReflectionException;
use ReflectionProperty;

use function array_key_exists;
use function property_exists;

abstract class AbstractEntity implements EntityInterface
{
    protected ?string $lastEntityAction = null;

    public function getLastEntityAction(): ?string
    {
        return $this->lastEntityAction;
    }

    public function setLastEntityAction(string $action): self
    {
        $this->lastEntityAction = $action;

        return $this;
    }

    public function getExchangeValue(array &$data, string $propertyName): void
    {
        if (property_exists($this, $propertyName)) {
            $return = $this->$propertyName;

            $data[$propertyName] = $return;
        }
    }

    /**
     * @throws ReflectionException
     */
    public function setExchangeValue(array $data, string $key): void
    {
        if (array_key_exists($key, $data)) {
            $this->$key = $this->assignValue($key, $data[$key]);
        }
    }

    /**
     * @throws ReflectionException
     */
    private function assignValue(string $key, mixed $value): mixed
    {
        if (null === $value) {
            return null;
        }

        return match ((new ReflectionProperty($this, $key))->getType()->getName()) {
            'boolean' => (bool) $value,
            'int' => (int) $value,
            'float' => (float) $value,
            'string' => (string) $value,
            default => $value,
        };
    }
}

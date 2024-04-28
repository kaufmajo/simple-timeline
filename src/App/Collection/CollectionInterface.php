<?php

declare(strict_types=1);

namespace App\Collection;

use Countable;
use Iterator;

interface CollectionInterface extends Countable, Iterator
{
    public function init(?array $collection = null): static;

    public function isFirst(): bool;

    public function isLast(): bool;

    public function getPreviousKey(): int|string|null;

    public function getNextKey(): int|string|null;

    public function fromMemory(string $key, string $value): false|string;
}

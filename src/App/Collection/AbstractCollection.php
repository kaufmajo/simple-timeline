<?php

declare(strict_types=1);

namespace App\Collection;

use function array_key_exists;
use function count;
use function current;
use function end;
use function key;
use function next;
use function reset;

abstract class AbstractCollection implements CollectionInterface
{
    private array $collection = [];
    public mixed $previous;
    public mixed $current;
    public mixed $next;
    public mixed $first;
    public mixed $last;
    private int|string|null $previousKey;
    private int|string|null $currentKey;
    private int|string|null $nextKey;
    public int|string|null $firstKey;
    public int|string|null $lastKey;
    public array $memory = [];

    public function __construct(?array $collection = null)
    {
        $this->init($collection);
    }

    public function init(?array $collection = null): static
    {
        if (null !== $collection) {
            $this->collection = $collection;
        }

        reset($this->collection);

        // first and last
        $this->setFirst($this->collection);
        $this->setLast($this->collection);

        // prev
        $this->previous    = null;
        $this->previousKey = null;

        // curr
        $this->current    = current($this->collection);
        $this->currentKey = key($this->collection);

        next($this->collection);

        // next
        $this->next    = current($this->collection);
        $this->nextKey = key($this->collection);

        return $this;
    }

    protected function setFirst(array $collection): void
    {
        $this->first    = reset($collection);
        $this->firstKey = key($collection);
    }

    protected function setLast(array $collection): void
    {
        $this->last    = end($collection);
        $this->lastKey = key($collection);
    }

    public function current(): mixed
    {
        return $this->current;
    }

    public function next(): void
    {
        // prev
        $this->previous    = $this->current;
        $this->previousKey = $this->currentKey;

        // curr
        $this->current    = $this->next;
        $this->currentKey = $this->nextKey;

        next($this->collection);

        // next
        $this->next    = current($this->collection);
        $this->nextKey = key($this->collection);
    }

    public function previous(): void
    {
        // next
        $this->next    = $this->current;
        $this->nextKey = $this->currentKey;

        // curr
        $this->current    = $this->previous;
        $this->currentKey = $this->previousKey;

        prev($this->collection);
        prev($this->collection);

        // prev
        $this->previous    = current($this->collection);
        $this->previousKey = key($this->collection);

        next($this->collection);
    }

    public function key(): string|int|null
    {
        return $this->currentKey;
    }

    public function getPreviousKey(): int|string|null
    {
        return $this->previousKey;
    }

    public function getNextKey(): int|string|null
    {
        return $this->nextKey;
    }

    public function valid(): bool
    {
        return array_key_exists($this->currentKey, $this->collection);
    }

    public function rewind(): void
    {
        $this->init();
    }

    public function count(): int
    {
        return count($this->collection);
    }

    public function isFirst(): bool
    {
        return $this->firstKey === $this->currentKey;
    }

    public function isLast(): bool
    {
        return $this->lastKey === $this->currentKey;
    }

    public function fromMemory(string $key, string $value): false|string
    {
        if (!in_array($value, $this->memory[$key] ?? [])) {

            $this->memory[$key][] = $value;

            return $value;
        }

        return false;
    }
}

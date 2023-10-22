<?php

declare(strict_types=1);

namespace App\Service;

use function array_keys;
use function array_map;
use function array_merge;
use function implode;
use function in_array;
use function is_string;
use function strpos;
use function substr;
use function trim;

class AttributeService
{
    protected array $attributes = [];

    public function __construct(array $attributes_from_element, array $attributes_from_helper = [])
    {
        $this->attributes = array_merge($attributes_from_element, $attributes_from_helper);
    }

    public function add(string $attribute, string $value): void
    {
        if (! empty($value)) {
            if (isset($this->attributes[$attribute])) {
                $this->attributes[$attribute] .= ' ' . $value;
            } else {
                $this->attributes[$attribute] = $value;
            }
        }
    }

    public function getAttrValue(string $attribute): ?string
    {
        $string = $this->getAttrKeyValue($attribute);

        return $string ? trim(substr($string, strpos($string, '=') + 1), '"') : '';
    }

    public function getAttrKeyValue(string $attribute): ?string
    {
        if (isset($this->attributes[$attribute])) {
            if ('name' === $attribute && (in_array('multiple', $this->attributes))) {
                return $attribute . '="' . $this->attributes[$attribute] . '[]"';
            }

            return $attribute . '="' . $this->attributes[$attribute] . '"';
        }

        return '';
    }

    public function getAllWithout(string|array $excludes = []): ?string
    {
        $excludes = is_string($excludes) ? [$excludes] : $excludes;

        return implode(' ', array_map(function ($attribute) use ($excludes) {
            if (! in_array($attribute, $excludes)) {
                return $this->getAttrKeyValue($attribute);
            } else {
                return '';
            }
        }, array_keys($this->attributes)));
    }
}

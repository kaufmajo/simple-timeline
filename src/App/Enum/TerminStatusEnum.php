<?php

declare(strict_types=1);

namespace App\Enum;

use function array_column;

enum TerminStatusEnum: string
{
    case GESTRICHEN = '3_gestrichen';
    case MITTEILUNG = '9_mitteilung';
    case NORMAL     = '0_normal';
    case WARNUNG    = '5_warnung';

    public function label(): string
    {
        return TerminStatusEnum::getLabelByName($this);
    }

    public static function getLabelByName(self $name): string
    {
        return match ($name) {
            TerminStatusEnum::GESTRICHEN => 'Gestrichen',
            TerminStatusEnum::MITTEILUNG => 'Mitteilung',
            TerminStatusEnum::NORMAL => 'Normal',
            TerminStatusEnum::WARNUNG => 'Warnung',
        };
    }

    public static function getLabelByValue(string $value): string
    {
        return TerminStatusEnum::getLabelByName(TerminStatusEnum::from($value));
    }

    public static function getNameArray(): array
    {
        return array_column(TerminStatusEnum::cases(), 'name');
    }

    public static function getValueArray(): array
    {
        return array_column(TerminStatusEnum::cases(), 'value');
    }
}

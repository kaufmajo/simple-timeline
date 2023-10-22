<?php

declare(strict_types=1);

namespace App\Enum;

use function array_column;

enum TerminAnsichtEnum: string
{
    case HEADER   = '3_header';
    case INFOBOX  = '2_infobox';
    case TIMELINE = '0_timeline';
    case NONE     = '1_none';

    public function label(): string
    {
        return TerminAnsichtEnum::getLabelByName($this);
    }

    public static function getLabelByName(self $name): string
    {
        return match ($name) {
            TerminAnsichtEnum::HEADER => 'Header',
            TerminAnsichtEnum::INFOBOX => 'Infobox',
            TerminAnsichtEnum::TIMELINE => 'Timeline',
            TerminAnsichtEnum::NONE => 'None',
        };
    }

    public static function getLabelByValue(string $value): string
    {
        return TerminAnsichtEnum::getLabelByName(TerminAnsichtEnum::from($value));
    }

    public static function getNameArray(): array
    {
        return array_column(TerminAnsichtEnum::cases(), 'name');
    }

    public static function getValueArray(): array
    {
        return array_column(TerminAnsichtEnum::cases(), 'value');
    }
}

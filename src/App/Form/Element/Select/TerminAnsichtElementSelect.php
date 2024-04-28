<?php

declare(strict_types=1);

namespace App\Form\Element\Select;

use App\Enum\TerminAnsichtEnum;
use Laminas\Form\Element;

class TerminAnsichtElementSelect extends Element\Select
{
    public function getValueOptionsFromConfig(): array
    {
        $return = [];

        foreach (TerminAnsichtEnum::cases() as $case) {
            $return[$case->value] = [
                'label' => $case->label(),
                'value' => $case->value,
            ];
        }

        return $return;
    }

    public function setValueOptionsFromConfig(): void
    {
        $this->setValueOptions($this->getValueOptionsFromConfig());
    }
}

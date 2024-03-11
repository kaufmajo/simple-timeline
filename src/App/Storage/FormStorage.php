<?php

declare(strict_types=1);

namespace App\Storage;

use Laminas\Form\FormInterface;

class FormStorage
{
    protected array $formArray = [];

    public function set(string $key, FormInterface $form): void
    {
        $this->formArray[$key] = $form;
    }

    public function get(string $key): ?FormInterface
    {
        if (isset($this->formArray[$key])) {
            return $this->formArray[$key];
        }

        return null;
    }
}

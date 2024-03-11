<?php

declare(strict_types=1);

namespace App\Traits\Aware;

use App\Storage;
use Laminas\Form\FormInterface;

trait FormStorageAwareTrait
{
    protected ?Storage\FormStorage $formStorage;

    public function setForm(string $key, FormInterface $form): void
    {
        if (! isset($this->formStorage)) {
            $this->formStorage = new Storage\FormStorage();
        }

        $this->formStorage->set($key, $form);
    }

    public function getForm(string $key): ?FormInterface
    {
        if (null === $this->formStorage) {
            $this->formStorage = new Storage\FormStorage();
        }

        return $this->formStorage->get($key);
    }
}

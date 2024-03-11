<?php

declare(strict_types=1);

namespace App\Validator;

use ArrayAccess;
use DateTime;
use Exception;
use Laminas\Validator\AbstractValidator;

class TerminDateValidator extends AbstractValidator
{
    const FLIP_ERROR = 'FLIP_ERROR';

    protected array $messageTemplates = [
        self::FLIP_ERROR => "Das Enddatum ist kleiner als das Startdatum", // "'%value%' ist kleiner als das Startdatum"
    ];

    /**
     * @throws Exception
     */
    public function isValid($value, null|array|ArrayAccess $context = null): bool
    {
        $this->setValue($value);

        if ((new DateTime($value)) < new DateTime($context['termin_datum_start'])) {
            $this->error(self::FLIP_ERROR);

            return false;
        }

        return true;
    }
}

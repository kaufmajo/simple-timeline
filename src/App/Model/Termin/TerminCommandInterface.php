<?php

declare(strict_types=1);

namespace App\Model\Termin;

use DatePeriod;

interface TerminCommandInterface
{
    public function insertTermin(TerminEntityInterface $terminEntity): int;

    public function updateTermin(TerminEntityInterface $terminEntity): int;

    public function deleteTermin(TerminEntityInterface $terminEntity): int;

    public function insertSerie(TerminEntityInterface $terminEntity): void;

    public function saveTermin(TerminEntityInterface $terminEntity): void;
}

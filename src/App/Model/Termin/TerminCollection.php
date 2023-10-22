<?php

declare(strict_types=1);

namespace App\Model\Termin;

use App\Collection\AbstractCollection;
use DateInterval;
use DateTime;
use Exception;

use function in_array;

class TerminCollection extends AbstractCollection
{
    private array $onlyOnceArray = [];

    public function isTerminForThisDay(): bool
    {
        return $this->current && $this->current['termin_id'];
    }

    public function isTerminForThisYear(): bool
    {
        return $this->getDatum()->format('Y') === (new DateTime())->format('Y');
    }

    public function isTerminToday(): bool
    {
        return $this->getDatum()->format('Y-m-d') === (new DateTime())->format('Y-m-d');
    }

    public function isTerminTomorrow(): bool
    {
        return $this->getDatum()->format('Y-m-d') === (new DateTime())->add(new DateInterval('P1D'))->format('Y-m-d');
    }

    public function isTerminThisWeek(): bool
    {
        $today = (new DateTime('now'))->setTime(0, 0);

        // 0 = sunday
        if (0 === (int) $today->format('w')) {
            $firstDay = (new DateTime('sunday this week'))->setTime(0, 0);
            $lastDay  = (new DateTime('sunday next week'))->setTime(23, 59);
        } else {
            $firstDay = (new DateTime('sunday last week'))->setTime(0, 0);
            $lastDay  = (new DateTime('sunday this week'))->setTime(23, 59);
        }

        return $this->getDatum() >= $firstDay && $this->getDatum() <= $lastDay;
    }

    public function isTerminVisibleInTimeline(bool $trackOnlyOnceFlag = true): bool
    {
        if (1 === (int) $this->current['termin_zeige_einmalig'] && in_array($this->current['termin_id'], $this->onlyOnceArray)) {
            return false;
        }

        if (1 === (int) $this->current['termin_zeige_einmalig'] && $trackOnlyOnceFlag) {
            $this->onlyOnceArray[] = $this->current['termin_id'];
        }

        return true;
    }

    public function getDatum(): DateTime
    {
        try {
            return new DateTime($this->current['datum_datum']);
        } catch (Exception) {
            return new DateTime('1900-01-01 00:00:00');
        }
    }
}

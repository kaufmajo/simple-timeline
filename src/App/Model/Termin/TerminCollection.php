<?php

declare(strict_types=1);

namespace App\Model\Termin;

use App\Collection\AbstractCollection;
use App\Enum\TerminStatusEnum;
use DateInterval;
use DateTime;
use Exception;

use function in_array;

class TerminCollection extends AbstractCollection
{
    private array $onlyOnceArray = [];

    public function hasTermin(): bool
    {
        $termin = $this->current;

        if ($termin && $termin['termin_id']) {

            if (1 === (int) $termin['termin_zeige_einmalig'] && in_array($termin['termin_id'], $this->onlyOnceArray)) {
                return false;
            }

            if (1 === (int) $termin['termin_zeige_einmalig']) {
                $this->onlyOnceArray[] = $termin['termin_id'];
            }

            return true;
        }

        return false;
    }

    public function isDatumToday(): bool
    {
        return $this->getDatum()->format('Y-m-d') === (new DateTime())->format('Y-m-d');
    }

    public function isDatumSunday(): bool
    {
        return 0 === (int) $this->getDatum()->format('w');
    }

    public function isDatumForThisYear(): bool
    {
        return $this->getDatum()->format('Y') === (new DateTime())->format('Y');
    }

    public function isDatumTomorrow(): bool
    {
        return $this->getDatum()->format('Y-m-d') === (new DateTime())->add(new DateInterval('P1D'))->format('Y-m-d');
    }

    public function isDatumThisWeek(): bool
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

    public function getDatum(): DateTime
    {
        try {
            return new DateTime($this->current['datum_datum']);
        } catch (Exception) {
            return new DateTime('1900-01-01 00:00:00');
        }
    }

    public function isGestrichen(array $termin = null): bool
    {
        $termin = $termin ?? $this->current();

        return TerminStatusEnum::GESTRICHEN->value === $termin['termin_status'];
    }

    public function isMitteilung(array $termin = null): bool
    {
        $termin = $termin ?? $this->current();

        return TerminStatusEnum::MITTEILUNG->value === $termin['termin_status'];
    }

    public function isNormal(array $termin = null): bool
    {
        $termin = $termin ?? $this->current();

        return TerminStatusEnum::NORMAL->value === $termin['termin_status'];
    }

    public function isWarnung(array $termin = null): bool
    {
        $termin = $termin ?? $this->current();

        return TerminStatusEnum::WARNUNG->value === $termin['termin_status'];
    }
}

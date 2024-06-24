<?php

declare(strict_types=1);

namespace App\Model\Termin;

use App\Collection\AbstractCollection;
use DateInterval;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use Exception;

use function in_array;

class TerminCollection extends AbstractCollection
{
    protected array $onlyOnceArray = [];

    protected ?DateTimeInterface $referenzDatum;

    public function __construct(DateTimeInterface|string|null $referenzDatum = null)
    {
        if ($referenzDatum) {
            $this->referenzDatum = is_string($referenzDatum) ? new DateTimeImmutable($referenzDatum) : $referenzDatum;
        } else {
            $this->referenzDatum = $referenzDatum;
        }
    }

    public function getReferenzDatum(): DateTimeInterface
    {
        return $this->referenzDatum;
    }

    public function getDatum(): DateTime
    {
        try {
            return new DateTime($this->current['datum_datum']);
        } catch (Exception) {
            return new DateTime('1900-01-01 00:00:00');
        }
    }

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

    public function isDatumFirstDayOfMonth(): bool
    {
        return 1 === (int) $this->getDatum()->format('j');
    }

    public function isDatumToday(): bool
    {
        return $this->getDatum()->format('Y-m-d') === (new DateTime())->format('Y-m-d');
    }

    public function isDatumSunday(): bool
    {
        return 0 === (int) $this->getDatum()->format('w');
    }

    public function isDatumTomorrow(): bool
    {
        return $this->getDatum()->format('Y-m-d') === (new DateTime())->add(new DateInterval('P1D'))->format('Y-m-d');
    }

    public function isReferenzThisYear(): bool
    {
        if ($this->referenzDatum instanceof DateTimeInterface) {
            return $this->referenzDatum->format('Y') === (new DateTime())->format('Y');
        } else {
            throw new Exception("No reference date given.");
        }
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

    public function isDatumWithinReferenceMonth(): bool
    {
        if ($this->referenzDatum instanceof DateTimeInterface) {
            return $this->referenzDatum->format('m') === $this->getDatum()->format('m');
        } else {
            throw new Exception("No reference date given.");
        }
    }
}

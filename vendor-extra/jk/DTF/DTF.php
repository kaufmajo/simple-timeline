<?php

declare(strict_types=1);

namespace JK\DTF;

use DateTimeInterface;
use DateTimeZone;
use IntlCalendar;
use IntlDateFormatter;
use IntlTimeZone;

class DTF
{
    private static ?DTF $instance = null;

    protected IntlDateFormatter $formatter;

    protected static ?string $local = "de-DE";

    protected static int $dateType = IntlDateFormatter::FULL;

    protected static int $timeType = IntlDateFormatter::FULL;

    protected static IntlTimeZone|DateTimeZone|string|null $timezone = null;

    protected static IntlCalendar|int|null $calendar = IntlDateFormatter::GREGORIAN;

    protected ?string $pattern = null;

    // The constructor is private
    // to prevent initiation with outer code.
    private function __construct()
    {
        $this->formatter = new IntlDateFormatter(
            self::$local,
            self::$dateType,
            self::$timeType,
            self::$timezone,
            self::$calendar
        );

        $this->pattern = $this->formatter->getPattern();
    }

    // The object is created from within the class itself
    // only if the class has no instance.
    protected static function getInstance(): ?DTF
    {
        if (self::$instance == null) {

            self::$instance = new DTF();
        }

        return self::$instance;
    }

    public static function format(DateTimeInterface $datetime, ?string $pattern = null): string
    {
        $dtf = self::getInstance();

        if ($pattern) {

            $dtf->formatter->setPattern($pattern);
        }

        $return = $dtf->formatter->format($datetime);

        if ($pattern) {

            $dtf->formatter->setPattern($dtf->pattern);
        }

        return $return;
    }
}

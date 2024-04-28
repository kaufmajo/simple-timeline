<?php

declare(strict_types=1);

namespace App\Service;

use DateInterval;
use DatePeriod;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;

use function explode;
use function gmdate;
use function ini_get;
use function round;
use function strlen;
use function substr;
use function time;

class HelperService
{
    const CONST_UNIT_KB   = 'KB';
    const CONST_UNIT_MB   = 'MB';
    const CONST_UNIT_GB   = 'GB';
    const CONST_UNIT_TB   = 'TB';
    const CONST_UNIT_BYTE = 'Byte';

    // -----------------------------------------------
    //
    //
    // format
    //
    //
    //-----------------------------------------------

    public static function format_filesize(int $bytes, ?string $unit = null): string
    {
        $units       = ['KB', 'MB', 'GB', 'TB'];
        $unit_suffix = self::CONST_UNIT_BYTE;

        for ($i = 0; $bytes >= 1024 && $i < 3 && $unit !== self::CONST_UNIT_BYTE; $i++) {
            $bytes      /= 1024;
            $unit_suffix = $units[$i];

            if ($unit === $units[$i]) {
                break;
            }
        }

        return round($bytes, 2) . ' ' . $unit_suffix;
    }

    public static function format_displayDate(string $datumStart, ?string $datumEnde = null, ?array $options = []): string
    {
        //Zend_Date::ISO_8601

        if (empty($datumStart) && empty($datumEnde)) {
            return '';
        }

        // init options
        $format_forOne   = $options['format_forOne'] ?? 'd.m.Y';
        $format_forLeft  = $options['format_forLeft'] ?? 'd.m';
        $format_forRight = $options['format_forRight'] ?? 'd.m.Y';

        // process
        if (null !== $datumEnde && $datumEnde != $datumStart) {
            return (new DateTime($datumStart))->format($format_forLeft) . ' - ' . (new DateTime($datumEnde))->format($format_forRight);
        } else {
            return (new DateTime($datumStart))->format($format_forOne);
        }
    }

    public static function format_displayTime(string $zeitStart, ?string $zeitEnde = null): string
    {
        if (empty($zeitStart) && empty($zeitEnde)) {
            return '';
        }

        $return = '';

        $zeitStartDateTime = null !== $zeitStart ? new DateTime($zeitStart) : null; //Zend_Date::TIME_FULL

        $zeitEndeDateTime = null !== $zeitEnde ? new DateTime($zeitEnde) : null; //Zend_Date::TIME_FULL

        if (null != $zeitStart) {
            $return = $zeitStartDateTime->format('H:i');
        }

        if (null == $zeitEnde && null != $zeitStart) {
            $return .= ' Uhr';
        } elseif (null != $zeitEnde) {
            $return .= ' bis ' . $zeitEndeDateTime->format('H:i') . ' Uhr';
        }

        return $return;
    }

    // ----------------------------------------------
    //
    //
    // strings
    //
    //
    //-----------------------------------------------

    /**
     * Substring without losing word meaning and
     * tiny words (length 3 by default) are included on the result.
     * "..." is added if result do not reach original string length
     */
    public static function string_substrWords(string $str, int $length, int $allowLastWordCharactersCount = 3): string
    {
        $newStr = '';

        foreach (explode(' ', $str) as $word) {
            $newStr .= ($newStr !== '' ? ' ' : '') . $word;

            if (strlen($word) > $allowLastWordCharactersCount && strlen($newStr) >= $length) {
                break;
            }
        }

        return $newStr . (strlen($newStr) < strlen($str) ? ' ...' : '');
    }

    // ----------------------------------------------
    //
    //
    // cache
    //
    //
    //-----------------------------------------------

    /**
     * @return array
     */
    public static function getBrowserCacheHeaders(int $lifetime): array
    {
        return [
            'Cache-Control' => 'private, must-revalidate, max-age=' . $lifetime,
            'Expires'       => gmdate('D, d M Y H:i:s', time() + $lifetime) . ' GMT',
            'Last-Modified' => gmdate('D, d M Y H:i:s', time()) . ' GMT',
        ];
    }

    // ----------------------------------------------
    //
    //
    // array
    //
    //
    //-----------------------------------------------

    // ----------------------------------------------
    //
    //
    // File
    //
    //
    //-----------------------------------------------

    public static function isPostMaxSizeReached()
    {
        // check that post_max_size has not been reached
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST) &&
            empty($_FILES) && $_SERVER['CONTENT_LENGTH'] > 0
        ) {
            $displayMaxSize = ini_get('post_max_size');

            switch (substr($displayMaxSize, -1)) {
                case 'G':
                    $displayMaxSize *= 1024;
                case 'M':
                    $displayMaxSize *= 1024;
                case 'K':
                    $displayMaxSize *= 1024;
            }

            $error = 'Posted data is too large. '
                . $_SERVER['CONTENT_LENGTH']
                . ' bytes exceeds the maximum size of '
                . $displayMaxSize . ' bytes.';

            die($error);
        }
    }

    // ----------------------------------------------
    //
    //
    // Attribute / Anchor
    //
    //
    //-----------------------------------------------

    public static function getAttribute(string $attribute, string|array $value): string
    {
        $value = is_array($value) ? $value : [$value];

        return $attribute . '="' . implode("", $value) . '"';
    }

    public static function getAnchorAttribute(int|string|false $anchor): string
    {
        return $anchor ? 'id="anchor-' . $anchor . '"' : '';
    }

    public static function getAnchorString(int|string|false $anchor): string
    {
        return $anchor ? 'anchor-' . $anchor : '';
    }

    // ----------------------------------------------
    //
    //
    // date
    //
    //
    //-----------------------------------------------

    public static function getMonthFirstDayForCalender(DateTime|string $date): DateTimeInterface
    {
        if (is_string($date)) {
            $date = $date ? new DateTimeImmutable($date) : new DateTimeImmutable();
        }

        $start = $date->modify('first day of this month');

        // is start day a monday
        return 1 === (int)$start->format('N') ? $start : $start->modify('last monday of last month');
    }

    public static function getMonthLastDayForCalender(DateTime|string $date): DateTimeInterface
    {
        if (is_string($date)) {
            $date = $date ? new DateTimeImmutable($date) : new DateTimeImmutable();
        }

        $ende = $date->modify('last day of this month');

        // is start day a monday
        return 7 === (int)$ende->format('N') ? $ende : $ende->modify('first sunday of next month');
    }

    public static function getSeriePeriod(string $startDatum, string $serienEnde, string $wiederholung): DatePeriod
    {
        return new DatePeriod(
            new DateTime($startDatum),
            DateInterval::createFromDateString(str_replace('[day]', (new DateTime($startDatum))->format('l'), $wiederholung)),
            new DateTime($serienEnde)
        );
    }
}

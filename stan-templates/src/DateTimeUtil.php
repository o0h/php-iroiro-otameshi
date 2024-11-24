<?php

declare(strict_types=1);

namespace App;

class DateTimeUtil
{
    /**
     * @template T of \DateTimeInterface
     * @param T $dateTime
     * @return T
     */
    public static function startOfDay(\DateTimeInterface $dateTime): \DateTimeInterface
    {
        return new $dateTime($dateTime->format('Y-m-d 00:00:00'));
    }

    public static function startOfDayNonTemplate(\DateTimeInterface $dateTime): \DateTimeInterface
    {
        return new $dateTime($dateTime->format('Y-m-d 00:00:00'));
    }
}

<?php

namespace App\Utils;

class ConvertDate
{
    public static function brazilianToAmerican(?string $stringDate): ?string
    {
        if (empty($stringDate)) return null;

        list($day, $month, $year) = explode('/', $stringDate);

        return (new \DateTime("{$year}-{$month}-{$day}"))
            ->format('Y-m-d');
    }

    public static function americanToBrazilian(?string $stringDate): ?string
    {
        if (empty($stringDate)) return null;

        return date('d/m/Y', strtotime($stringDate));
    }
}

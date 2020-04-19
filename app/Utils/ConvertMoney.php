<?php

namespace App\Utils;

class ConvertMoney
{
    public static function brazilianToDouble(?string $stringBRFormat): ?float
    {
        if (empty($stringBRFormat)) return null;
        return floatval(
            str_replace(',', '.',
                str_replace('.', '', $stringBRFormat)
            )
        );
    }

    public static function doubleToBrazilian(?float $double): string
    {
        if (empty($double)) return '';
        return number_format($double, 2, ',', '.');
    }
}

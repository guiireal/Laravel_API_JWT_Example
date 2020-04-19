<?php

namespace App\Utils;

class CleanField
{
    public static function clear(?string $document): string
    {
        if (empty($document)) return '';
        return str_replace(['.', '-', '/', '(', ')', '', "'"], '', $document);
    }
}

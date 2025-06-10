<?php
namespace App\Helpers;

class StringHelper
{
    public static function trim(?string $value): string
    {
        return trim((string) $value);
    }

    public static function implodeArray(array $items, string $separator): string
    {
        return implode($separator, $items);
    }
}
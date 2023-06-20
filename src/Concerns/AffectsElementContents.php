<?php

namespace Konsulting\Matrix\Concerns;

trait AffectsElementContents
{
    /**
     * Check if an array consists only of null elements.
     *
     * @param array $array
     * @return bool
     */
    public static function arrayHasOnlyNullElements($array)
    {
        foreach ($array as $elements) {
            if ( ! is_null($elements)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Trim whitespace from all string items in the matrix.
     *
     * @param array $matrix
     * @return array
     */
    public static function trimStrings($matrix)
    {
        return static::map($matrix, function ($item) {
            return is_string($item) ? trim(html_entity_decode($item), " \t\n\r\0\x0B\xC2\xA0") : $item;
        });
    }

    /**
     * Convert empty strings to null.
     *
     * @param $matrix
     * @return array
     */
    public static function emptyStringToNull($matrix)
    {
        return static::map($matrix, function ($item) {
            return $item === '' ? null : $item;
        });
    }

    /**
     * Convert the decimal separator character for all matrix elements.
     *
     * @param array  $matrix
     * @param string $from
     * @param string $to
     * @return array
     */
    public static function convertDecimalSeparators($matrix, $from = ',', $to = '.')
    {
        return static::map($matrix, function ($element) use ($from, $to) {
            return preg_replace("/^\s*(-?\d+)[\{$from}](\d+)\s*$/", "$1{$to}$2", $element);
        });
    }
}

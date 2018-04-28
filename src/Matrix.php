<?php

namespace Konsulting\Matrix;

class Matrix
{
    /**
     * Remove any elements from the input array that are arrays containing only null elements.
     *
     * @param array $matrix
     * @return array
     */
    public static function removeNullRows($matrix)
    {
        $output = [];

        foreach ($matrix as $row) {
            if ( ! static::arrayHasOnlyNullElements($row)) {
                $output[] = $row;
            }
        }

        return $output;
    }

    /**
     * Remove any columns from the input array that contain only null elements.
     *
     * @param array $matrix
     * @return array
     */
    public static function removeNullColumns($matrix)
    {
        return static::callTransposed('removeNullRows', $matrix);
    }

    /**
     * Remove rows and columns that only contain null elements.
     *
     * @param array $matrix
     * @return array
     */
    public static function removeNullRowsAndColumns($matrix)
    {
        return static::removeNullRows(
            static::removeNullColumns($matrix));
    }

    /**
     * Transpose a two dimensional array by 90 degrees.
     *
     * @param array $matrix
     * @return array
     */
    public static function transpose($matrix)
    {
        if (count($matrix) > 1) {
            return array_map(null, ...$matrix);
        }

        return array_map(function ($item) {
            return (array) $item;
        }, $matrix[0]);
    }

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
            return is_string($item) ? trim($item) : $item;
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
     * Map each item of the matrix according to the callback.
     *
     * @param array    $matrix
     * @param callable $callback
     * @return array
     */
    public static function map($matrix, $callback)
    {
        return array_map(function ($row) use ($callback) {
            return array_map($callback, $row);
        }, $matrix);
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
            return preg_replace("/^\s*(\d+)[\{$from}](\d+)\s*$/", "$1{$to}$2", $element);
        });
    }

    /**
     * Insert a row into the matrix at the specified offset.
     *
     * @param array[] $matrix
     * @param array   $row The row to insert
     * @param int     $offset
     * @return array[]
     */
    public static function insertRow($matrix, $row, $offset)
    {
        $row = array_values($row);
        array_splice($matrix, $offset, null, [$row]);

        return $matrix;
    }

    /**
     * Insert a column into the matrix at the specified offset.
     *
     * @param array[] $matrix
     * @param array   $column The column to insert
     * @param int     $offset
     * @return array[]
     */
    public static function insertColumn($matrix, $column, $offset)
    {
        return static::callTransposed('insertRow', $matrix, $column, $offset);
    }

    /**
     * Call a function with the subject matrix transposed. Returns the matrix in its original orientation.
     *
     * @param string|callable $callable  The name of a method on this class or a callable.
     * @param array[]         $matrix
     * @param array           $arguments The arguments to pass to the callable. May be given as an array or listed as
     *                                   additional arguments to this method
     * @return array[]
     */
    protected static function callTransposed($callable, $matrix, $arguments = [])
    {
        $arguments = count(func_get_args()) > 3
            ? array_slice(func_get_args(), 2)
            : (array) $arguments;

        $transposed = static::transpose($matrix);
        $result = is_string($callable) && method_exists(static::class, $callable)
            ? static::$callable($transposed, ...$arguments)
            : $callable($transposed, ...$arguments);

        return static::transpose($result);
    }
}

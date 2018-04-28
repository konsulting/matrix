<?php

namespace Konsulting\Matrix\Concerns;

trait AffectsRowsAndColumns
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
}

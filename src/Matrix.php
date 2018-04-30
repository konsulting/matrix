<?php

namespace Konsulting\Matrix;

use Konsulting\Matrix\Concerns\AffectsElementContents;
use Konsulting\Matrix\Concerns\AffectsRowsAndColumns;

class Matrix
{
    use AffectsRowsAndColumns,
        AffectsElementContents;

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
        $arguments = static::getDynamicArguments(func_get_args(), 2);
        $transposed = static::transpose($matrix);
        $result = is_string($callable) && method_exists(static::class, $callable)
            ? static::$callable($transposed, ...$arguments)
            : $callable($transposed, ...$arguments);

        return static::transpose($result);
    }

    /**
     * Extract an array from the method arguments, which may be an array passed as the final argument, or as separate
     * additional arguments.
     *
     * @param array $methodArgs
     * @param int   $numberStaticArgs
     * @return array
     */
    protected static function getDynamicArguments(array $methodArgs, int $numberStaticArgs)
    {
        return count($methodArgs) > $numberStaticArgs + 1
            ? array_slice($methodArgs, $numberStaticArgs)
            : (array) $methodArgs;
    }
}

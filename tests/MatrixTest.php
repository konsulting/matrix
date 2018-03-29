<?php

namespace Konsulting\Matrix\Tests;

use Konsulting\Matrix\Matrix;
use PHPUnit\Framework\TestCase;

class MatrixTest extends TestCase
{
    /** @test */
    public function it_removes_null_rows()
    {
        $array = [
            [null, null, null],
            [0, null, 0],
            [null, null, null],
            [1, 1, 1],
            ['', null, null],
        ];
        $expected = [
            [0000, null, 0000],
            [1, 1, 1],
            ['', null, null],
        ];

        $this->assertEquals($expected, Matrix::removeNullRows($array));
    }

    /** @test */
    public function it_removes_null_columns()
    {
        $array = [
            [null, null, null, null, ''],
            [null, 0, null, null, null],
            [null, null, null, null],
            [null, 1, 1, null, null],
        ];
        $expected = [
            [null, null, ''],
            [0, null, null],
            [null, null, null],
            [1, 1, null],
        ];

        $this->assertEquals($expected, Matrix::removeNullColumns($array));
    }

    /** @test */
    public function it_removes_null_rows_and_columns()
    {
        $array = [
            [null, null, null, null, ''],
            [null, 0, null, null, null],
            [null, null, null, null, null],
            [null, 1, 1, null, null],
        ];
        $expected = [
            [null, null, ''],
            [0, null, null],
            [1, 1, null],
        ];

        $this->assertEquals($expected, Matrix::removeNullRowsAndColumns($array));
    }

    /** @test */
    public function it_transposes_a_multidimensional_array()
    {
        $array = [
            [1, 2, 3],
            [4, 5, 6],
            [7, 8, 9],
        ];
        $expected = [
            [1, 4, 7],
            [2, 5, 8],
            [3, 6, 9],
        ];

        $output = Matrix::transpose($array);

        $this->assertEquals($expected, $output);
    }

    /** @test */
    public function it_transposes_a_one_dimensional_array()
    {
        $array = [[1, 2, 3]];
        $expected = [[1], [2], [3]];

        $output = Matrix::transpose($array);

        $this->assertEquals($expected, $output);
    }

    /** @test */
    public function it_checks_if_an_array_only_has_null_elements()
    {
        $this->assertTrue(Matrix::arrayHasOnlyNullElements([null, null, null]));
        $this->assertFalse(Matrix::arrayHasOnlyNullElements([null, null, '']));
        $this->assertFalse(Matrix::arrayHasOnlyNullElements([null, []]));
        $this->assertFalse(Matrix::arrayHasOnlyNullElements([null, 0]));
    }

    /** @test */
    public function it_trims_all_string_items()
    {
        $array = [
            [' 1 ', '  2', 3],
            [4, ' fi ve  ', null],
            [7, null, true],
        ];
        $expected = [
            ['1', '2', 3],
            [4, 'fi ve', null],
            [7, null, true],
        ];

        $trimmedArray = Matrix::trimStrings($array);

        $this->assertEquals($expected, $trimmedArray);

        // Check that values keep their type
        $this->assertTrue(is_int($trimmedArray[2][0]));
        $this->assertNull($trimmedArray[1][2]);
        $this->assertTrue(is_bool($trimmedArray[2][2]));
    }

    /** @test */
    public function it_converts_empty_strings_to_null()
    {
        $this->assertEquals([[null, false, '0']], Matrix::emptyStringToNull([['', false, '0']]));
    }

    /** @test */
    public function it_converts_decimal_separator_characters()
    {
        $array = [
            ['  1,99', '2,0', '0,00  '],
            ['6,7,8 ', ' test ', null],
        ];
        $expected = [
            ['1.99', '2.0', '0.00'],
            ['6,7,8 ', ' test ', null],
        ];

        $this->assertEquals($expected, Matrix::convertDecimalSeparators($array));
    }
}

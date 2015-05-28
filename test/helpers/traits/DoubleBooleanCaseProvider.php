<?php
namespace test\helpers\traits;

/**
 * Trait for generic double-boolean dataprovider
 */
trait DoubleBooleanCaseProvider
{

    public function doubleBooleanCaseProvider()
    {
        return [
            [true, true],
            [true, false],
            [false, true],
            [false, false],
        ];
    }
}

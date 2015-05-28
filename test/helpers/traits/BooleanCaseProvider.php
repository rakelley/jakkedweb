<?php
namespace test\helpers\traits;

/**
 * Trait for unit tests which need a simple boolean case provider
 */
trait BooleanCaseProvider
{

    public function booleanCaseProvider()
    {
        return [[true], [false]];
    }
}

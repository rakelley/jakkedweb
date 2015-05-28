<?php
namespace test\helpers\traits;

/**
 * Trait for View tests with need to test ::constructView
 */
trait TestsConstructView
{

    protected function standardConstructViewTest()
    {
        $this->testObj->constructView();
        $content = $this->readAttribute($this->testObj, 'viewContent');

        $this->assertTrue(strlen($content) > 1);
    }
}

<?php
namespace test\helpers\traits;

use \test\helpers\PHPUnitUtil as Utility;

/**
 * Implements common shared testing functionality for testcases written for
 * classes which use \rakelley\jhframe\\traits\view\MakesSubViews
 */
trait MockSubViews
{

    /**
     * Standard implementation of testGetSubViewList
     * 
     * @return array
     */
    protected function standardGetSubViewListTest()
    {
        $list = Utility::callMethod($this->testObj, 'getSubViewList');
        $this->assertInternalType('array', $list);
        return $list;
    }


    /**
     * Mocks subviews for use in testing methods which depend on them
     * 
     * @param  boolean $return If true, return subviews instead of setting
     *                         test object property
     * @return array|void
     */
    protected function mockSubViews($return=false)
    {
        $subViews = $this->standardGetSubViewListTest();
        array_walk(
            $subViews,
            function(&$value) {
                $value = 'lorem ipsum dolor subview';
            }
        );

        if ($return) {
            return $subViews;
        } else {
            Utility::setProperties(['subViews' => $subViews], $this->testObj);
        }
    }
}

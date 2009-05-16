<?php
class Test2 extends ztest\UnitTestCase
{
    public function test_foo() {
        assert_equal('foo', 'foo');
    }
    
    public function test_bar() {
        assert_not_equal('bar', 'foo');
    }
}
?>
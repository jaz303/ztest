<?php
class FooException extends Exception {}

class TestAssertions extends ztest\UnitTestCase
{
    public function test_ensure() {
        ensure(true);
        assert_fails(function() { ensure(false); });
    }
    
    public function test_each() {
        assert_each(array(10, 11, 12), function($i) { return $i > 5; });
        assert_fails(function() {
            assert_each(array(5, 6, 7), function($i) { return $i > 5; });
        });
    }
    
    public function test_assert_object() {
        assert_object(new stdClass);
        assert_fails(function() { assert_object(1); });
        assert_fails(function() { assert_object(array()); });
    }
    
    public function test_assert_array() {
        assert_array(array());
        assert_fails(function() { assert_array(1); });
        assert_fails(function() { assert_array(new stdClass); });
    }
    
    public function test_scalar() {
        assert_scalar(1);
        assert_fails(function() { assert_scalar(array()); });
        assert_fails(function() { assert_scalar(new stdClass); });
    }
    
    public function test_equal() {
        assert_equal(1, 1);
        assert_equal(1, "1");
        assert_fails(function() { assert_equal(1, 2); });
        assert_fails(function() { assert_equal(1, "2"); });
    }
    
    public function test_not_equal() {
        assert_not_equal(1, 2);
        assert_not_equal(1, "2");
        assert_fails(function() { assert_not_equal(1, 1); });
        assert_fails(function() { assert_not_equal(1, "1"); });
    }
    
    public function test_identical() {
        assert_identical(1, 1);
        assert_identical(true, true);
        assert_fails(function() { assert_identical(1, "1"); });
        assert_fails(function() { assert_identical(true, 1); });
    }
    
    public function test_match() {
        assert_match('/[a-z]{3}/', 'abc');
        assert_fails(function() { assert_match('/[a-z]{3}/', '111'); });
    }
    
    public function test_null() {
        assert_null(null);
        assert_fails(function() { assert_null(0); });
    }
    
    public function test_not_null() {
        assert_not_null(0);
        assert_fails(function() { assert_not_null(null); });
    }
    
    public function test_throws() {
        assert_throws('FooException', function() { throw new FooException; });
        assert_fails(function() { assert_throws('FooException', function() { throw new Exception; }); });
    }

	public function test_output() {
		assert_output(array(
			'foo '		=> 'foo',
			' foo'		=> 'foo',
			' foo '		=> 'foo',
			'foo'		=> 'foo'
		), function($v) { return trim($v); });
		
		assert_fails(function() {
			assert_output(array(
				'foo'		=> 'foo '
			), function($v) { return trim($v); });
		});
	}
}
?>
ztest - a unit testing library for PHP5.3
=========================================

(c) 2009 Jason Frame [jason@onehackoranother.com]  
Released under the MIT License

ztest is new unit testing library designing to take advantage of the new features of PHP5.3 - namely namespaces and closures. It was extracted from the BasePHP library, a perpetual work-in-progress.

Feature Highlights
------------------

  * assertions defined as global functions - no more typing $this->assert_blah()
  * run tests via console, with output similar to Ruby's `Test::Unit`
  * use closures to elegantly test collections and exceptions
  * automatic population of test suites based on directory contents and defined subclasses.

Roadmap
-------

Depending on interest, adoption and motivation, any of the following:

  * Mock objects
  * Spec-like syntax
  * Web page/documentation etc.

Example Test Case
-----------------

	class MyTestCase extends ztest\UnitTestCase
	{
		public function setup() {
			// do setup mojo, run before each test
		}
		
		public function teardown() {
			// do teardown mojo, run after each test
		}
	    
		public function test_foo_returns_true() {
	        ensure(foo());
	    }

	    public function test_every_element_returned_by_bar_is_gt_5() {
	        assert_each(bar(), function($i) { return $i > 5; });
	    }

	    public function test_baz_throws_bleem_exception() {
	        assert_throws('BleemException', function() { baz(); });
	    }
	}

Self Testing
------------

From the ztest directory (remember to run with PHP5.3):

	jason@ratchet ztest  $ /usr/local/bin/php examples/run_tests.php 
	NOTE: Passed assertions for this suite will be less than total.
	** This is OK (as long as there are no 'F's) **

	..............

	Summary: 14/14 tests passed, 37/55 assertions
	0.001845s
  
Usage
-----

__Note:__ ztest is designed to be run from the command line and does not presently support running test suites from the browser. This functionality is, however, easy to implement by anyone who desires it.

The easiest way to use ztest is to dump all of your test classes in a directory structure then copy and modify the supplied template file located in `template/run_tests.php`. Each test file should have a `.php` extension, and you may define as many test classes per file as you wish. ztest will recursively scan the directory for tests and then run each in turn.

Example test runner script:

	// Adjust this to point to the ztest library
	// (relative to the current working directory)
	require 'ztest/ztest.php';
	
	// Create test suite
	$suite = new ztest\TestSuite("My application's unit tests");

	// Recursively scan the 'test' directory and require() all PHP source files
	// Again, 'test' is relative to the current working directory.
	$suite->require_all('test');

	// Add non-abstract subclasses of ztest\TestCase as test-cases to be run
	$suite->auto_fill();

	// And away we go.
	$suite->run(new ztest\ConsoleReporter);
	
Once you've created your test runner, invoke it as you would any other PHP script:

	jason@ratchet ztest $ /usr/local/bin/php run_tests.php
	
Assertions
----------

Available assertions are defined in `inc/assertions.php`. These should be self-explanatory. Perhaps the only thing to note is that there is no vanilla `assert()` function - I opted for `ensure()` instead of monkeying with PHP's own `assert()` implementation.

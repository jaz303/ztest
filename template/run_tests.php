<?php
set_time_limit(0);

// Do any setup stuff specific to your test suite here, e.g. setting up include
// paths, establish database connections.

//
// Here's the testing mojo ->

// adjust this to point to wherever ztest is located
require '../ztest.php';

$suite = new ztest\TestSuite("My application's unit tests");

// Recursively scan the 'test' directory and require() all PHP source files
$suite->require_all('test');

// Add non-abstract subclasses of ztest\TestCase as test-cases to be run
$suite->auto_fill();

// Create a reporter and enable color output
$reporter = new ztest\ConsoleReporter;
$reporter->enable_color();

// Go, go, go
$suite->run($reporter);
?>
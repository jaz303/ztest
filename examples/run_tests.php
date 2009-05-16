<?php
require dirname(__FILE__) . '/../ztest.php';

$suite = new ztest\TestSuite("ztest example test suite");

echo "NOTE: Passed assertions for this suite will be less than total.\n** This is OK (as long as there are no 'F's) **\n\n";

$suite->require_all(dirname(__FILE__) . '/test');
$suite->auto_fill();
$suite->run(new ztest\ConsoleReporter);
?>
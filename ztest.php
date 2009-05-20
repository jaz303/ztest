<?php
define('ZTEST_VERSION', '0.0.2');

$ztest_manifest = array(
    'exceptions',
    'TestCase',
    'UnitTestCase',
    'TestSuite',
    'Reporter',
    'ConsoleReporter',
    'test_invokers',
    'assertions',
    'mocking'
);

foreach ($ztest_manifest as $ztest_file) {
    require dirname(__FILE__) . "/inc/$ztest_file.php";
}

unset($ztest_manifest);
unset($ztest_file);
?>
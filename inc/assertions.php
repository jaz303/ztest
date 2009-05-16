<?php
//
// Assertions are written as normal functions and we use a static property on
// Test_Base to keep track of state. The reason: it's less to type. No one likes
// writing $this->assert() over and over.

function pass() {
	ensure(true);
}

function fail($msg = "") {
	ensure(false, $msg);
}

/**
 * Assert
 * 
 * @param $v value to be checked for truthiness
 * @param $msg message to report on failure
 */
function ensure($v, $msg = "") {
    if (!$v) {
		ztest\TestCase::$reporter->assert_fail();
		throw new ztest\AssertionFailed($msg);
	} else {
		ztest\TestCase::$reporter->assert_pass();
	}
}

function assert_each($iterable, $test, $msg = "") {
    foreach ($iterable as $i) {
        ensure($test($i));
    }
}

function assert_object($v, $msg = "") {
	ensure(is_object($v), $msg);	
}

function assert_array($v, $msg = "") {
	ensure(is_array($v), $msg);	
}

function assert_scalar($v, $msg = "") {
    ensure(is_scalar($v), $msg);
}

function assert_not_equal($l, $r, $msg = "") {
    ensure($l != $r, $msg);
}

function assert_equal($l, $r, $msg = "") {
    ensure($l == $r, $msg);
}

function assert_identical($l, $r, $msg = "") {
	ensure($l === $r, $msg);	
}

function assert_equal_strings($l, $r, $msg = "") {
	ensure(strcmp($l, $r) === 0);	
}

function assert_match($regex, $r, $msg = "") {
	ensure(preg_match($regex, $r), $msg);	
}

function assert_null($v, $msg = "") {
	ensure($v === null, $msg);
}

function assert_not_null($v, $msg = "") {
	ensure($v !== null, $msg);	
}

// NOTE: this assertion swallows all exceptions
function assert_throws($exception_class, $lambda, $msg = '') {
    try {
        $lambda();
        fail($msg);
    } catch (Exception $e) {
        if (is_a($e, $exception_class)) {
            pass();
        } else {
            fail($msg);
        }
    }
}

/**
 * This one's a bit dubious - it tests that an assertion made by the supplied
 * lambda fails. It primarily exists for self-testing the ztest library, and
 * using it causes the displayed assertion stats to be incorrect.
 */
function assert_fails($lambda, $msg = '') {
    $caught = false;
    try {
        $lambda();
    } catch (ztest\AssertionFailed $e) {
        $caught = true;
        pass();
    }
    if (!$caught) {
        throw new ztest\AssertionFailed($msg);
    }
}
?>
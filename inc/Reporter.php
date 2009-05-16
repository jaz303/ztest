<?php
namespace ztest;

abstract class Reporter
{
	protected $incidents		= array();
	
	protected $start_time;
	protected $exec_time;
	
	protected $test_total		= 0;
	protected $test_passes		= 0;
	protected $test_failures	= 0;
	protected $test_errors		= 0;
	
	protected $assert_total		= 0;
	protected $assert_passes	= 0;
	protected $assert_failures	= 0;
	
	public function start() {
		$this->start_time = microtime(true);	
	}
	
	public function end() {
		$this->exec_time = microtime(true) - $this->start_time;
	}
	
	public function test_enter(TestInvoker $invoker) {
		$this->test_total++;
		$this->report_test_enter($invoker);
	}
	
	public function test_exit(TestInvoker $invoker) {
		$this->report_test_exit($invoker);	
	}
	
	public function test_pass() {
		$this->test_passes++;
		$this->report_test_pass();
	}
	
	public function test_fail(AssertionFailed $eaf) {
		$this->incidents[] = $eaf;
		$this->test_failures++;
		$this->report_test_fail($eaf);
	}
	
	public function test_error(\Exception $e) {
		$this->incidents[] = $e;
		$this->test_errors++;
		$this->report_test_error($e);
	}
	
	public function assert_pass() {
		$this->assert_total++;
		$this->assert_passes++;
		$this->report_assert_pass();
	}
	
	public function assert_fail() {
		$this->assert_total++;
		$this->assert_failures++;
		$this->report_assert_fail();
	}
	
	public function summary() {
		$this->report_summary();	
	}
	
	protected function report_test_enter(TestInvoker $invoker) {}
	protected function report_test_exit(TestInvoker $invoker) {}

	protected function report_test_pass() {}
	protected function report_test_fail(AssertionFailed $eaf) {}
	protected function report_test_error(\Exception $e) {}
	
	protected function report_assert_pass() {}
	protected function report_assert_fail() {}
	
	protected function report_summary() {}
}
?>

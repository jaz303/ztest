<?php
namespace ztest;

class ConsoleReporter extends Reporter
{
	protected function report_test_pass() {
		echo ".";
	}
	
	protected function report_test_fail(AssertionFailed $eaf) {
		echo "F";
	}
	
	protected function report_test_error(\Exception $e) {
		echo "E";	
	}
	
	protected function report_summary() {
		echo "\n";
		
		foreach ($this->incidents as $e) {
			echo "\n";
			$message = $e->getMessage() ? $e->getMessage() : '(no message)';
			if ($e instanceof AssertionFailed) {
				echo "Failure: $message\n";
			} else {
				$class = get_class($e);
				echo "Error: $class {$message}\n";
			}
			echo $e->getTraceAsString() . "\n";
		}
		
		echo "\nSummary: ";
		echo "{$this->test_passes}/{$this->test_total} tests passed";
		echo ", {$this->assert_passes}/{$this->assert_total} assertions";
		echo sprintf("\n%fs\n", $this->exec_time);
	}
}
?>

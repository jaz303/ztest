<?php
namespace ztest;

class ConsoleReporter extends Reporter
{
    private static $colors = array(
        'red'       => "\033[31m",
        'green'     => "\033[32m",
        'blue'      => "\033[34m",
        'yellow'    => "\033[33m",
        'default'   => "\033[0m"
    );
    
    private $use_color = false;
    
    public function enable_color() {
        $this->use_color = true;
    }
    
	protected function report_test_pass() {
		echo $this->wrap('.', 'green');
	}
	
	protected function report_test_fail(AssertionFailed $eaf) {
		echo $this->wrap('F', 'red');
	}
	
	protected function report_test_error(\Exception $e) {
		echo $this->wrap('E', 'red');	
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
		
		echo $this->wrap_by_success(
		    "{$this->test_passes}/{$this->test_total} tests passed",
		    $this->test_passes,
		    $this->test_total);
		echo ", ";
		echo $this->wrap_by_success(
		    "{$this->assert_passes}/{$this->assert_total} assertions",
		    $this->assert_passes,
		    $this->assert_total);
        
		echo sprintf("\n%fs\n", $this->exec_time);
	}
	
	private function wrap($string, $color) {
	    if ($this->use_color) {
	        return self::$colors[$color] . $string . self::$colors['default'];
	    } else {
	        return $string;
	    }
	}
	
	private function wrap_by_success($string, $success, $total) {
	    return $this->wrap($string, $success == $total ? 'green' : 'yellow');
	}
}
?>

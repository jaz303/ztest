<?php
namespace ztest;

abstract class TestCase
{
	public static $reporter	= null;
	
	public function get_name() {
		return get_class($this);	
	}
		
	public function run(Reporter $reporter) {
		self::$reporter = $reporter;
		foreach ($this->get_test_invokers() as $ti) {
			$this->run_one($ti);	
		}
		self::$reporter = null;
	}
	
	protected function run_one(TestInvoker $ti) {
		self::$reporter->test_enter($ti);
		try {
			$this->do_run_one($ti);
			self::$reporter->test_pass();
		} catch (AssertionFailed $eaf) {
			self::$reporter->test_fail($eaf);
		} catch (\Exception $e) {
			self::$reporter->test_error($e);
		}
		self::$reporter->test_exit($ti);
	}
	
	/**
	 * Returns an array of Test_Invoker instances which, combined, will run all
	 * tests declared by this test class. The default behaviour is to return
	 * a collection of Test_MethodInvoker for all public methods prefixed by
	 * 'test_'
	 */
	protected function get_test_invokers() {
		$reflector = new \ReflectionClass($this);
		$all = array();
		foreach ($reflector->getMethods() as $method) {
			if ($method->isPublic() && preg_match('/^test_/', $method->getName())) {
				$all[] = new MethodInvoker($method);
			}
		}
		return $all;
	}
	
	protected abstract function do_run_one(TestInvoker $ti);
}
?>
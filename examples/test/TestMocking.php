<?php
class TestMocking extends ztest\UnitTestCase
{
    public function test_mock_generate_returns_mock_specification() {
        ensure(Mock::generate() instanceof MockSpecification);
    }
    
    public function test_mock_generate_assigns_class_name() {
        assert_equal('TestMocking1', Mock::generate('TestMocking1')->get_class_name());
    }
    
    public function test_anonymous_mocks_receive_unique_class_names() {
        assert_not_equal(
            Mock::generate()->get_class_name(),
            Mock::generate()->get_class_name()
        );
    }
    
    public function test_writing_class_generates_class_of_same_name() {
        $class_name = 'TestMocking2';
        ensure(!class_exists($class_name));
        Mock::generate($class_name)->write();
        ensure(class_exists($class_name));
    }
    
    public function test_extending_class_generates_subclass() {
        
        Mock::generate('TestMockingExtendParent')->write();
        Mock::generate('TestMockingExtendChild')->extend('TestMockingExtendParent')->write();
        
        ensure(is_subclass_of('TestMockingExtendChild', 'TestMockingExtendParent'));
        ensure(new TestMockingExtendChild instanceof TestMockingExtendParent);
        
    }
    
    public function test_construct_returns_mock_instance() {
        ensure(
            Mock::generate('TestMockingConstruct')->construct()
            instanceof
            TestMockingConstruct
        );
    }
    
    public function test_receives_creates_mock_method_definition() {
        ensure(Mock::generate()->receives('foo') instanceof MockMethodSpecification);
    }
    
    public function test_mock_method_can_be_called() {
        $obj = Mock::generate()->receives('bar')->construct();
        $obj->bar();
        pass();
    }
    
    public function test_calling_unexpected_method_fails_test() {
        $obj = Mock::generate()->receives('baz')->construct();
        assert_fails(function() use ($obj) { $obj->bleem(); });
    }
}
?>
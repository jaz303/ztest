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
}
?>
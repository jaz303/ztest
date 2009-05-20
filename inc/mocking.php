<?php
class Mock
{
    private static $id;
    
    public static function generate($class_name = null) {
        if ($class_name === null) {
            $id = self::$id++;
            $class_name = "__GeneratedMock{$id}__";
        }
        return new MockSpecification($class_name);
    }
}

class MockSpecification
{
    private $class_name;
    
    public function __construct($class_name) {
        $this->class_name = $class_name;
    }
    
    public function get_class_name() {
        return $this->class_name;
    }
}
?>
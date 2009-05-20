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
    private $superclass     = null;
    
    private $written        = false;
    
    public function __construct($class_name) {
        $this->class_name = $class_name;
    }
    
    public function get_class_name() {
        return $this->class_name;
    }
    
    public function extend($superclass) {
        $this->superclass = $superclass;
        return $this;
    }
    
    public function write() {
        if (!$this->written) {
            eval($this->to_php());
            $this->written = true;
        }
    }
    
    public function construct() {
        $this->write();
        return new $this->class_name;
    }
    
    public function to_php() {
        
        $php = "class {$this->class_name}";
        
        if ($this->superclass) {
            $php .= " extends {$this->superclass}";
        }
        
        $php .= " {\n";
        
        $php .= "}\n";
        
        return $php;
        
    }
}
?>
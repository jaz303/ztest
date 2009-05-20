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
    
    public static function method_matches_pattern($method, $pattern) {
        // TODO: support wildcards
        return strcmp($method, $pattern) === 0;
    }
}

class MockSpecification
{
    private static $specs   = array();
    
    public static function lookup($instance, $pattern = null) {
        $out = self::$specs[get_class($instance)];
        if ($pattern) $out = $out->methods[$pattern];
        return $out;
    }
    
    private $class_name;
    private $superclass     = null;
    private $interfaces     = array();
    private $methods        = array();
    
    private $written        = false;
    
    public function __construct($class_name) {
        $this->class_name = $class_name;
        self::$specs[$this->class_name] = $this;
    }
    
    public function get_class_name() {
        return $this->class_name;
    }
    
    public function extend($superclass) {
        $this->superclass = $superclass;
        return $this;
    }
    
    public function implement($interface) {
        $this->interfaces[] = $interface;
        return $this;
    }
    
    public function receives($method_pattern) {
        $mock_method = new MockMethodSpecification($this, $method_pattern);
        $this->methods[$method_pattern] = $mock_method;
        return $mock_method;
    }
    
    //
    //
    
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
        
        if (count($this->interfaces)) {
            $php .= ' implements ' . implode(', ', $this->interfaces);
        }
        
        $php .= " {\n";
        
        //
        // Setup __call() hook to receive all expected methods and throw
        // AssertionFailed on receiving other methods.
        
        $php .= "    private \$memory = array();\n";
        
        $patterns = array();
        foreach ($this->methods as $m) $patterns[] = $m->get_pattern();
        
        $php .= "    private \$method_patterns = " . var_export($patterns, true) . ";\n";
        
        $php .= "    public function __call(\$method, \$args) {\n";
        $php .= "        foreach (\$this->method_patterns as \$pattern) {\n";
        $php .= "            if (Mock::method_matches_pattern(\$method, \$pattern)) {\n";
        $php .= "                \$this->memory[] = array(\$method, \$args);\n";
        $php .= "                if (\$closure = MockSpecification::lookup(\$this, \$pattern)->get_closure()) {\n";
        $php .= "                    return call_user_func_array(\$closure, \$args);\n";
        $php .= "                } else {\n";
        $php .= "                    return;\n";
        $php .= "                }\n";
        $php .= "            }\n";
        $php .= "        }\n";
        $php .= "        throw new ztest\\AssertionFailed(\"Unexpected method '\$method' called\");\n";
        $php .= "    }\n";
        
        //
        // Now implement any interfaces
        
        $iface_methods = array();
        
        foreach ($this->interfaces as $iface) {
            $reflection = new ReflectionClass($iface);
            foreach ($reflection->getMethods() as $method) {
                $args = array();
                foreach ($method->getParameters() as $param) {
                    $arg = '';
                    if ($param->isArray()) {
                        $arg .= 'array ';
                    } elseif ($pclass = $param->getClass()) {
                        $arg .= $pclass->getName() . ' ';
                    }
                    if ($param->isPassedByReference()) $arg .= '&';
                    $arg .= '$' . $param->getName();
                    if ($param->allowsNull()) {
                        $arg .= ' = null';
                    } elseif ($param->isOptional()) {
                        $arg .= ' = ' . var_export($param->getDefaultValue(), true);
                    }
                    $args[] = $arg;
                }
                $arg_list = implode(', ', $args);
                $php .= "    public function {$method->getName()}($arg_list) {\n";
                $php .= "        return \$this->__call('{$method->getName()}', func_get_args());\n";
                $php .= "    }\n";
            }
        }
        
        $php .= "}\n";
        
        return $php;
        
    }
}

class MockMethodSpecification
{
    private $mock;
    private $pattern;
    private $closure;
    
    public function __construct(MockSpecification $mock, $pattern) {
        $this->mock = $mock;
        $this->pattern = $pattern;
    }
    
    public function get_pattern() {
        return $this->pattern;
    }
    
    public function get_closure() {
        return $this->closure;
    }
    
    public function returning($thing) {
        if ($thing instanceof Closure) {
            $this->closure = $thing;
        }
        return $this;
    }
    
    public function back() {
        return $this->mock;
    }
    
    public function __call($method, $args) {
        return call_user_func_array(array($this->mock, $method), $args);
    }
}
?>
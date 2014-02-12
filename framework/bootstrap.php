<?php

class Bootstrap {
    public $server;
    public $file;
    public $response;
    public $index_dir;
    protected $dependencies = array();
    
    public function __construct(){
        require_once __DIR__."/lib/controller.php";
        require_once __DIR__."/lib/server.php";
        require_once __DIR__."/lib/response.php";
        require_once __DIR__."/lib/request.php";
        $this->server = new server();
        $this->response = new response();
        $this->request = new Request($this->server);
        
        $this->index_dir = getcwd();
        
        set_error_handler(array($this, "throwError"));
    }
    
    public function throwError($errno, $errstr, $errfile, $errline){
        throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
    }
    
    public function automaticLoader($component_name){
        if(!file_exists($this->index_dir."/components/".$component_name.".class.php")){
            throw new Exception("$component_name dependence not satisfied. file not found");
        }
        require_once $this->index_dir."/components/".$component_name.".class.php";
        if(!class_exists("component_".$component_name)){
            throw new Exception("$component_name dependence not satisfied. class not found");
        }
        if(!array_key_exists($component_name, $this->dependencies)){
            $this->dependencies[$component_name] = $this->instanciateClass("component_".$component_name);
        }
        return $this->dependencies[$component_name];
    }
    
    public function instanciateClass($class_name){
        $reflectionClass = new ReflectionClass($class_name);
        if(!$reflectionClass->hasMethod("__construct")){
            return new $class_name;
        }
        $reflection = new ReflectionMethod($class_name,"__construct");
        $class_args = array();
        foreach($reflection->getParameters() as $reflectionParameter){
            $class_args[] = $this->automaticLoader($reflectionParameter->name);
        }
        
        $reflected_class = new ReflectionClass($class_name);
        $class_instance = $reflected_class->newInstanceArgs($class_args);
        
        return $reflected_class->newInstanceArgs($class_args);
    }
    
    public function countDependencies(){
        return count($this->dependencies);
    }
}
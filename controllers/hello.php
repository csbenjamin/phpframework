<?php

class hello extends controller {
    
    public function execute($name = "nobody"){
        $this->response->foo = new stdClass;
        $this->response->foo->bar = 123;
        $this->response->params = func_get_args();
        $this->response->hello = $name;
    }

}
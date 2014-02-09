<?php

class foo_bar extends Controller {
    
    function __construct($first){
        $this->first = $first;
    }
    
    function execute(){
        $params = func_get_args();
        if(isset($params[0]) && $params[0] == "error"){
            echo $id; #__getline__
        }
        if(isset($params[0]) && $params[0] == "useinjection"){
            $this->response->unseinjection = $this->first->doIt("stringfromcontroller");
            return;
        }
        if(isset($params[0])){
            $this->response->set($params[0]);
            return;
        }
        $this->response->set(false);
    }    
}
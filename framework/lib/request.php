<?php 

class request {
    protected $_server;
    protected $_req;
    function __construct($server){
        $this->_server = $server;
        
        $req = json_encode(file_get_contents('php://input'));
        $this->_req = gettype($req) == "object" ? $req : new stdClass;
        
    }
    
    public function getBody(){
        return $this->_req;
    }
    
    public function setBody($req){
        if(gettype($req) == "string"){
            $req = json_encode($req);
        }
         
        $this->_req = gettype($req) == "object" ? $req : new stdClass;
    }
    
    public function __get($key){
        if(isset($this->_req->$key)){
            return $this->_req->$key;
        }
        return null;
    }
    
    function __set($key, $value){
        $this->_req->$key = $value;
    }
    
    public function getMethod(){
        return strtolower($this->server->REQUEST_METHOD);
    }
    
    public function isPost(){
        return $this->getMethod == "post";
    }
    
    public function isGet(){
        return $this->getMethod == "get";
    }
    
    
}
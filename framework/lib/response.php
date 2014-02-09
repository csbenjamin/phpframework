<?php

class Response {
    
    public $_res;
    public $_msg = array();
    public $_status_code;
    public $_headers = array();
    
    function __construct(){
        $this->_status_code = 200;
    }
    
    function __set($key, $value){
        if(gettype($this->_res) !== "array" || !array_key_exists("__response_array__",$this->_res)){
            $this->_res = array("__response_array__"=>null);
        }
        $this->_res[$key] = $value;
    }
    
    function set($key, $value = null){
        if( count(func_get_args()) == 1 ){
            $this->_res = $key;
            return;
        }
        if(gettype($this->_res) !== "array" || !array_key_exists("__response_array__",$this->_res)){
            $this->_res = array("__response_array__"=>null);
        }
        $this->_res[$key] = $value;
    }
    
    function __get($key){
        if(isset($this->_res[$key])){
            return $this->_res[$key];
        }
        return null;
    }
    
    function notFound(){
        $this->_status_code = 404;
        $this->_res = array("Not"=>"Found");
    }
    
    function error($e){
        $this->_status_code = 500;
        $this->_res = array();
        $this->_res["message"] = $e->getMessage();
        $this->_res["file"] = $e->getFile();
        $this->_res["line"] = $e->getLine();
        
    }
    
    function setHeader($header){
        $this->_headers[] = $header;
    }
    
    function setStatusHeader(){
        switch($this->_status_code){
            case 404:
                $this->setHeader("HTTP/1.0 404 Not Found");
                break;
            case 200:
                $this->setHeader("HTTP/1.0 200 OK");
                break;
            case 500:
                $this->setHeader("HTTP/1.0 500 Internal Server Error");
                break;
        }
    }
    
    function sendHeaders(){
        $this->setStatusHeader();
        foreach($this->_headers as $header){
            header($header);
        }
    }
    
    function __toString(){
        $res = $this->_res;
        if(gettype($res) == "array" && array_key_exists("__response_array__",$res)){
            unset($res["__response_array__"]);
        }
        return json_encode(
            array(
                "res"=>$res,
                "msg"=>$this->_msg
            )
        );
    }
}
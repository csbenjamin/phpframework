<?php

class ServerMock{
    
    protected $server = array(
        "REQUEST_METHOD" => "GET",
        "SCRIPT_NAME" => "/index.php"
    );
    
    public function setUrl($url){
        $this->REQUEST_URI = $url;
    }
    public function __set($key, $value){
        $this->server[$key] = $value;
    }
    
    public function __get($key){
        if(isset($this->server[$key])){
            return $this->server[$key];
        }
        
        return null;
        
    }
}
<?php

class Server{
    
    public function __get($key){
        if(isset($_SERVER[$key])){
            return $_SERVER[$key];
        }
        
        return null;
        
    }
}
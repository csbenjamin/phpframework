<?php

class component_second2{
    function __construct($third){
        $this->third = $third;
    }
    
    function doIt($var){
        $var .= "__theSecond2PutThis";
        return $this->third->doIt($var);
    }
}
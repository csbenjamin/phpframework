<?php

class component_second1{
    function __construct($third){
        $this->third = $third;
    }
    
    function doIt($var){
        $var .= "__theSecond1PutThis";
        return $this->third->doIt($var);
    }
}
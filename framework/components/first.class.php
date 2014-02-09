<?php

class component_first{
    function __construct($second1, $second2){
        $this->second1 = $second1;
        $this->second2 = $second2;
    }
    
    function doIt($var){
        return array($this->second1->doIt($var), $this->second2->doIt($var));
    }
}
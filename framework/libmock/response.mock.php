<?php
require_once __DIR__."/../lib/response.php";

class ResponseMock extends Response {
    
    
    function sendHeaders(){
        $this->setStatusHeader();
    }
    
}
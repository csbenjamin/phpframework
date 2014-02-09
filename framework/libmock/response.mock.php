<?php
require_once __DIR__."/../lib/response.php";

class ResponseMock extends Response {
    
    
    function header($header){
        $this->_headers[] = $header;
    }
    
}
<?php
require_once __DIR__."/bootstrap.php";
class BootstrapMock extends Bootstrap {
    
    public function __construct(){
        require_once __DIR__."/lib/controller.php";
        require_once __DIR__."/libmock/server.mock.php";
        require_once __DIR__."/libmock/response.mock.php";
        require_once __DIR__."/lib/request.php";
        $this->server = new ServerMock();
        $this->response = new ResponseMock();
        $this->request = new Request($this->server);
        
        $this->index_dir = __DIR__;
        
        set_error_handler(array($this, "throwError"));
    }
}
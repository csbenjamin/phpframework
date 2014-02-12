<?php
require_once __DIR__."/../framework/bootstrap.mock.php";
require_once __DIR__."/../framework/app.php";
class baseControllerTest extends PHPUnit_Framework_TestCase{
    public $app;
    public $bootstrap;
    
    protected function setUp(){
        parent::setUp();
        $this->bootstrap = new BootstrapMock();
        $this->app = new App($this->bootstrap);
    }
    
}
<?php
require_once __DIR__."/../lib/request.php";
require_once __DIR__."/../libmock/server.mock.php";

class requestTest extends PHPUnit_Framework_TestCase{
    public $server;
    public $request;
    protected function setUp(){
        $this->server = new ServerMock;
        $this->request = new Request($this->server);
    }
    public function test(){
        $this->assertEquals(new stdClass, $this->request->getBody());
        
        $this->request->setBody('{}');
        $this->assertEquals(new stdClass, $this->request->getBody());
        
        $this->request->setBody('{"foo":"bar"}');
        $obj = new stdClass;
        $obj->foo = "bar";
        $this->assertEquals( $obj, $this->request->getBody());
        
        $this->request->setBody(clone $obj);
        $this->assertEquals($obj, $this->request->getBody());
        
        $this->request->setBody(clone $obj);
        $this->assertEquals("bar", $this->request->foo);
        
        $this->request->hello = "world";
        $obj->hello = "world";
        $this->assertEquals($obj, $this->request->getBody());
        $this->assertEquals("world", $this->request->hello);
        
        $this->request->setBody(null);
        $this->assertEquals(new stdClass, $this->request->getBody());
        
        $this->assertEquals("get", $this->request->getMethod());
        $this->assertEquals(true, $this->request->isGet());
        $this->assertEquals(false, $this->request->isPost());
        
        $this->server->REQUEST_METHOD = "post";
        
        $this->assertEquals("post", $this->request->getMethod());
        $this->assertEquals(false, $this->request->isGet());
        $this->assertEquals(true, $this->request->isPost());
        
        $_GET["hello"] = "world";
        $this->request = new Request($this->server);
        $this->assertEquals((object)array("hello"=>"world"), $this->request->getBody());
        
        $_POST["hello"] = "world!";
        $this->request = new Request($this->server);
        $this->assertEquals((object)array("hello"=>"world!"), $this->request->getBody());
        
        $_GET = array();
        $_POST["hello"] = "world!";
        $this->request = new Request($this->server);
        $this->assertEquals((object)array("hello"=>"world!"), $this->request->getBody());
        
        $_POST["hello"] = "world!";
        $_POST["hello2"] = "world!";
        $this->request = new Request($this->server);
        $this->assertEquals((object)array("hello"=>"world!","hello2"=>"world!"), $this->request->getBody());
        
        
        
    }
}
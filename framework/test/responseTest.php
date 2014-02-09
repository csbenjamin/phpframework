<?php
require_once __DIR__."/../libmock/response.mock.php";

class responseTest extends PHPUnit_Framework_TestCase{
    public $response;
    protected function setUp(){
        $this->response = new ResponseMock;
    }
    public function test(){
        $this->assertEquals(200, $this->response->_status_code);
        
        $this->assertEquals('{"res":null,"msg":[]}', (string)$this->response);
        
        $this->response->foo = "bar";
        $this->response->obj = new stdClass;
        $this->response->obj->foo = 1;
        $this->assertEquals('{"res":{"foo":"bar","obj":{"foo":1}},"msg":[]}', (string)$this->response);
        
        $this->response->set(null);
        $this->assertEquals('{"res":null,"msg":[]}', (string)$this->response);
        
        $this->response->set(3);
        $this->assertEquals('{"res":3,"msg":[]}', (string)$this->response);
        
        $this->response->set(true);
        $this->assertEquals('{"res":true,"msg":[]}', (string)$this->response);
        
        $this->response->set(false);
        $this->assertEquals('{"res":false,"msg":[]}', (string)$this->response);
        
        $this->response->set(array());
        $this->assertEquals('{"res":[],"msg":[]}', (string)$this->response);
        $this->response->sendHeaders();
        $this->assertEquals(array("HTTP/1.0 200 OK"), $this->response->_headers);
        $this->response->_headers = array();
        
        $this->response->notFound();
        $this->assertEquals('{"res":{"Not":"Found"},"msg":[]}', (string)$this->response);
        $this->assertEquals(404, $this->response->_status_code);
        $this->response->sendHeaders();
        $this->assertEquals(array("HTTP/1.0 404 Not Found"), $this->response->_headers);
        $this->response->_headers = array();
        
        $e = $this->getMockBuilder('stdClass')
            ->setMethods(array("getMessage","getFile","getLine"))
            ->disableOriginalConstructor()
            ->getMock();

        // Configure the stub.
        $e->expects($this->any())
            ->method('getMessage') 
            ->will($this->returnValue('some error message'));
            
        $e->expects($this->any())
            ->method('getFile')
            ->will($this->returnValue('a/b/c.php'));
            
        $e->expects($this->any())
            ->method('getLine')
            ->will($this->returnValue(23));
            
        $this->response->error($e);
        $this->assertEquals('{"res":{"message":"some error message","file":"a\/b\/c.php","line":23},"msg":[]}', (string)$this->response);
        $this->assertEquals(500, $this->response->_status_code);
        $this->response->sendHeaders();
        $this->assertEquals(array("HTTP/1.0 500 Internal Server Error"), $this->response->_headers);
        $this->response->_headers = array();
        
        $this->response->_status_code = 200;
        $this->response->setHeader("Some: thing");
        $this->response->sendHeaders();
        $this->assertEquals(array("Some: thing","HTTP/1.0 200 OK"), $this->response->_headers);
        $this->response->_headers = array();
        
    }
}
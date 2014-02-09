<?php
require_once __DIR__."/../baseTest.php";
class helloTest extends baseControllerTest{
    protected function setUp(){
        parent::setUp();
        //do some stuff
    }
    
    public function test_get(){
        
        $this->bootstrap->server->setUrl("/hello");
        $result = $this->app->run();
        $expected = '{"res":{"foo":{"bar":123},"params":[],"hello":"nobody"},"msg":[]}';
        $this->assertEquals($expected, $result);
        
        $this->bootstrap->server->setUrl("/hello/world");
        $result = $this->app->run();
        $expected = '{"res":{"foo":{"bar":123},"params":["world"],"hello":"world"},"msg":[]}';
        $this->assertEquals($expected, $result);
        
        
        
    }
    
    /**
     * @TODO  some tests with post method
     */
    
}
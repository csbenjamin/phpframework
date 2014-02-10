<?php
require __DIR__."/../bootstrap.mock.php";
require __DIR__."/../app.php";
class routeTest extends PHPUnit_Framework_TestCase{
    public $app;
    public $bootstrap;
    
    protected function setUp(){
        parent::setUp();
        $this->bootstrap = new BootstrapMock();
        $this->app = new App($this->bootstrap);
    }
    
    public function test_route(){
        
        $this->bootstrap->server->setUrl("/foo/bar");
        list($file_name, $class_name, $params) = $this->app->route();
        $this->assertEquals($this->bootstrap->index_dir."/controllers/foo/bar.php", $file_name, "file_name on script name=/index.php and request uri=/foo/baa");
        $this->assertEquals("foo_bar", $class_name, "class_name on script name=/index.php and request uri=/foo/baa");
        $this->assertEquals(array(), $params, "params is empty on script name=/index.php and request uri=/foo/baa");
        
        $this->bootstrap->server->setUrl("/foo/bar/a");
        list($file_name, $class_name, $params) = $this->app->route();
        $this->assertEquals($this->bootstrap->index_dir."/controllers/foo/bar.php", $file_name, "file_name on script name=/index.php and request uri=/foo/baa/a");
        $this->assertEquals("foo_bar", $class_name, "class_name on script name=/index.php and request uri=/foo/baa/a");
        $this->assertEquals(array("a"), $params, "params is not empty on script name=/index.php and request uri=/foo/baa/a");
        
        
        $this->bootstrap->server->SCRIPT_NAME = "/index.php";
        $this->bootstrap->server->setUrl("/index.php/foo/bar");
        list($file_name, $class_name, $params) = $this->app->route();
        $this->assertEquals($this->bootstrap->index_dir."/controllers/foo/bar.php", $file_name, "file_name on script name=/index.php and request uri=/foo/baa");
        $this->assertEquals("foo_bar", $class_name, "class_name on script name=/index.php and request uri=/foo/baa");
        $this->assertEquals(array(), $params, "params is empty on script name=/index.php and request uri=/foo/baa");
        
        $this->bootstrap->server->setUrl("/index.php/foo/bar/a");
        list($file_name, $class_name, $params) = $this->app->route();
        $this->assertEquals($this->bootstrap->index_dir."/controllers/foo/bar.php", $file_name, "file_name on script name=/index.php and request uri=/foo/bar/a");
        $this->assertEquals("foo_bar", $class_name, "class_name on script name=/index.php and request uri=/foo/bar/a");
        $this->assertEquals(array("a"), $params, "params is not empty on script name=/index.php and request uri=/foo/bar/a");
        
        
        $this->bootstrap->server->SCRIPT_NAME = "/framework/index.php";
        $this->bootstrap->server->setUrl("/framework/index.php/foo/bar");
        list($file_name, $class_name, $params) = $this->app->route();
        $this->assertEquals($this->bootstrap->index_dir."/controllers/foo/bar.php", $file_name, "file_name");
        $this->assertEquals("foo_bar", $class_name, "class_name");
        $this->assertEquals(array(), $params, "params is empty");
        
        $this->bootstrap->server->setUrl("/framework/index.php/foo/bar/a");
        list($file_name, $class_name, $params) = $this->app->route();
        $this->assertEquals($this->bootstrap->index_dir."/controllers/foo/bar.php", $file_name, "file_name");
        $this->assertEquals("foo_bar", $class_name, "class_name");
        $this->assertEquals(array("a"), $params, "params is not empty");
        
        
        $this->bootstrap->server->SCRIPT_NAME = "/framework/index.php";
        $this->bootstrap->server->setUrl("/framework/foo/bar");
        list($file_name, $class_name, $params) = $this->app->route();
        $this->assertEquals($this->bootstrap->index_dir."/controllers/foo/bar.php", $file_name, "file_name");
        $this->assertEquals("foo_bar", $class_name, "class_name");
        $this->assertEquals(array(), $params, "params is empty");
        
        $this->bootstrap->server->setUrl("/framework/foo/bar/a");
        list($file_name, $class_name, $params) = $this->app->route();
        $this->assertEquals($this->bootstrap->index_dir."/controllers/foo/bar.php", $file_name, "file_name");
        $this->assertEquals("foo_bar", $class_name, "class_name");
        $this->assertEquals(array("a"), $params, "params is not empty");
        
        
        $this->bootstrap->server->SCRIPT_NAME = "/index.php";
        $this->bootstrap->server->setUrl("/hello");
        list($file_name, $class_name, $params) = $this->app->route();
        $this->assertEquals($this->bootstrap->index_dir."/controllers/hello.php", $file_name, "file_name");
        $this->assertEquals("hello", $class_name, "class_name");
        $this->assertEquals(array(), $params, "params is empty");
        
        $this->bootstrap->server->setUrl("/hello/a");
        list($file_name, $class_name, $params) = $this->app->route();
        $this->assertEquals($this->bootstrap->index_dir."/controllers/hello.php", $file_name, "file_name");
        $this->assertEquals("hello", $class_name, "class_name");
        $this->assertEquals(array("a"), $params, "params is not empty");
        
        
        $this->bootstrap->server->SCRIPT_NAME = "/index.php";
        $this->bootstrap->server->REQUEST_URI = "/index.php/foo/bar/_asdf";
        $exceptionMessage = "";
        try{
            list($file_name, $class_name, $params) = $this->app->route();
        }catch(Exception $e){
            $exceptionMessage = $e->getMessage();
        }
        $this->assertEquals("Not allowed folder, file or param in router starting with _", $exceptionMessage, "Exception _");
        
        
        $this->bootstrap->server->setUrl("/index.php/abc/bar/asdf");
        $exceptionMessage = "";
        try{
            list($file_name, $class_name, $params) = $this->app->route();
        }catch(Exception $e){
            $exceptionMessage = $e->getMessage();
        }
        $this->assertEquals("Not Found", $exceptionMessage, "Not Found");
        
        
    }
    
    public function test_run(){
        
        $this->bootstrap->server->SCRIPT_NAME = "/index.php";
        
        $this->bootstrap->response->set(null);
        $this->bootstrap->server->setUrl("/hello");
        $result = $this->app->run();
        $this->assertEquals('{"res":{"foo":{"bar":123},"params":[],"hello":"nobody","method":"get"},"msg":[]}',$result);
        
        $this->bootstrap->response->set(null);
        $this->bootstrap->server->setUrl("/hello/world");
        $result = $this->app->run();
        $this->assertEquals('{"res":{"foo":{"bar":123},"params":["world"],"hello":"world","method":"get"},"msg":[]}',$result);
        
        
        $this->bootstrap->response->set(null);
        $this->bootstrap->server->setUrl("/foo/bar");
        $result = $this->app->run();
        $this->assertEquals('{"res":false,"msg":[]}',$result);
        
        $this->bootstrap->response->set(null);
        $this->bootstrap->server->setUrl("/foo/bar/something");
        $result = $this->app->run();
        $this->assertEquals('{"res":"something","msg":[]}',$result);
        
        $this->bootstrap->response->set(null);
        $this->bootstrap->server->setUrl("/foo/bar/useinjection");
        $result = $this->app->run();
        $this->assertEquals('{"res":{"unseinjection":["stringfromcontroller__theSecond1PutThis__theThirdPutThis","stringfromcontroller__theSecond2PutThis__theThirdPutThis"]},"msg":[]}',$result);
        
        $this->bootstrap->response->set(null);
        $this->bootstrap->server->setUrl("/foo/bar/error");
        $result = $this->app->run();
        $dir = preg_replace("/\//","\\/",dirname(__DIR__));
        $content = file_get_contents(dirname(__DIR__)."/controllers/foo/bar.php");
        $content = explode("__getline__", $content)[0];
        $line = substr_count($content, "\n")+1;
        $expect = '{"res":{"message":"Undefined variable: id","file":"'.$dir.'\/controllers\/foo\/bar.php","line":'.$line.'},"msg":[]}';
        $this->assertEquals($expect,$result);
        
        $this->bootstrap->response->set(null);
        $this->bootstrap->server->setUrl("/i/dont/exist");
        $result = $this->app->run();
        $this->assertEquals('{"res":{"Not":"Found"},"msg":[]}',$result);
        
    }
    
}
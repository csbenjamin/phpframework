<?php

class App {
    protected $bootstrap;
    protected $server;
    protected $file;
    protected $response;
    
    function __construct($bootstrap){
        $this->bootstrap = $bootstrap;
        $this->server = $bootstrap->server;
        $this->response = $bootstrap->response;
        $this->request = $bootstrap->request;
    }
    function run(){
        try{
            list($file_name, $class_name, $params) = $this->route();
        }catch(Exception $e){
            if($e->getMessage() == "Not Found"){
                $this->response->notFound();
                $this->close();
            }else{
                return $this->doSomethingWithError($e);
            }
        }
        
        require_once $file_name;
        if(!class_exists($class_name)){
            return $this->doSomethingWithError(new Exception("The class $class_name must be declared in file $file_name"));
        }
        
        try{
            $this->controller = $this->bootstrap->instanciateClass($class_name);
        }catch(Exception $e){
            return $this->doSomethingWithError($e);
        }
        
        $this->response->set(null); //assert the response is null yet
        
        $this->controller->response = $this->response;
        $this->controller->request = $this->request;
        $this->controller->app = $this;
        
        try{
            if(!(new ReflectionClass($class_name))->hasMethod("execute")){
                throw new Exception("You need to implement the execute method in $class_name class");
            }
            $execute_method = new ReflectionMethod ( $class_name, "execute" );
            $required_params_count = $execute_method->getNumberOfRequiredParameters();
            if(count($params)< $required_params_count){
                throw new Exception("$class_name class require at least $required_params_count param");
            }
            call_user_func_array(array($this->controller,"execute"), $params);
            
        }catch(Exception $e){
            return $this->doSomethingWithError($e);
        }
        return $this->close(); //caso close não tenha sido chamado dentro da classe controller.
    }

    function close(){
        $this->response->sendHeaders();
        return (string)$this->response;
    }

    function doSomethingWithError($e){
        //do the work
        $this->response->error($e);
        return $this->close();
        
    }
    
    function route(){
        //aqui fariamos a rota
        $error = null;
        $path = "";
        $script_file = basename($this->server->SCRIPT_NAME);
        $script_dir = dirname($this->server->SCRIPT_NAME);
        $request_uri = preg_replace("/^\/*|\/*$/","",substr($this->server->REQUEST_URI, strlen($script_dir)));
        if( substr($request_uri,0,strlen($script_file)) == $script_file ){
            $path = preg_replace("/^\/*/","",substr($request_uri,strlen($script_file)+1));
        }else{
            $path = preg_replace("/^\/*/","",$request_uri);
        }
        $path_array = explode("/",$path);
        $params = array();
        foreach($path_array as $o){
            if(substr($o,0,1) == "_"){
                throw new Exception("Not allowed folder, file or param in router starting with _");
            }
        }
        while(!file_exists($this->bootstrap->index_dir."/controllers/".implode("/",$path_array).".php") && count($path_array)>0){
            array_unshift($params,array_pop($path_array));
        }
        if(count($path_array)===0){
            throw new Exception("Not Found");
        }
        
        $file_name = $this->bootstrap->index_dir."/controllers/".implode("/",$path_array).".php";
        $class_name = implode("_",$path_array);
        
        return array($file_name, $class_name, $params);
    }
}
<?php
class Route{
    private $routes = []; 
    private $methods = [];
    private $docRoot = null;
    public function __construct($docRoot) {
        $this->docRoot = $docRoot;
    } 

    public function goto($uri,$method=null){  
        if($uri == '' || $uri == null) $uri = '/';                            
        $this->routes[] = $this->docRoot.$uri;  
        $this->methods[] = $method;     
    } 

    private function methodAllowed(){
        $requestMethod = $_SERVER['REQUEST_METHOD']; 
        $reqMethod = ['GET','POST'];
        if(!in_array($requestMethod,$reqMethod)){
            return false;
        } else{
            return true;
        }
    }
   
    public function run(){
        if(!$this->methodAllowed()){
            die('<h1>Http method not allowed</h1>');
        }
        $url = parse_url($_SERVER['REQUEST_URI']);            
        $path = $url['path'];
        $query = isset($url['query'])?$url['query']:'';
        $match = false;
        foreach($this->routes as $key => $value){                                          
            if(preg_match("#^$value$#i",$path)){
                $this->methods[$key]();
                $match = true;
            }
        }
        if(!$match){
            echo '<h1>Page Not Found</h1>';                
        }
    } 
 }

 ?>
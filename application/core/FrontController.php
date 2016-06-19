<?php

class FrontController{
    protected $_controller, $_action, $_params;
    static $_instance;

    const DEF_CONTROLLER = "IndexController";
    const DEF_ACTION = "IndexAction";



    private function __construct() {
        $request = filter_input(INPUT_SERVER,
                                'REQUEST_URI',
                                FILTER_SANITIZE_STRING);
        $splitRequest = explode('/', trim($request, '/'));
        $this->getControllerName($splitRequest);
        $this->getActionName($splitRequest);
        $this->getAllParams($splitRequest);
    }

    public static function getInstance(){
        if(!(self::$_instance instanceof self)){
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    private function getControllerName($splitRequest){
        if(!empty($splitRequest[0])){
            $this->_controller = ucfirst($splitRequest[0]."Controller");
        }else{
            $this->_controller = self::DEF_CONTROLLER;

        }
    }
    
    private function getActionName($splitRequest){
        if(!empty($splitRequest[1])){
            $this->_action = ucfirst($splitRequest[1]."Action");
        }else{   
            $this->_action = self::DEF_ACTION;
        } 
    }

    
    private function getAllParams($splitRequest){
        $keys = array();
        $values = array();
        if(!empty($splitRequest[2]) && !empty($splitRequest[3])){
            $keys = $values = array();
            $countParams = count($splitRequest);
            for($i=2; $i<$countParams; $i++){
                if($i % 2 == 0){
                    $keys[] = $splitRequest[$i];
                }else{
                    $values[] = $splitRequest[$i];
                }
            }
            $this->_params = array_combine($keys, $values);
            return true;
        }else{
            $this->_params = null;
            return false;
        }
        
    }
        
    public function wrongAction($refClass){
        $controller = $refClass->newInstance();
        $method = $refClass->getMethod("notFoundAction");
        $method->invoke($controller);
    }
    
    public function wrongController(){
        $refClass = new ReflectionClass(self::DEF_CONTROLLER);
        $this->wrongAction($refClass);
    }
    

    public function route(){
        if(class_exists($this->getController())){
            $refClass = new ReflectionClass($this->getController());
            if($refClass->hasMethod($this->getAction())){
                $controller = $refClass->newInstance();
                $method = $refClass->getMethod($this->getAction());
                $method->invoke($controller, $this->_params);
            }else{
                $refClass = new ReflectionClass(self::DEF_CONTROLLER);
                $this->wrongAction($refClass);
                /*throw new Exception("Wrong Action!");*/
            }
        }else{
            $this->wrongController();
            /*throw new Exception("Wrong Controller!");*/
            
        }
    }
    
    public function getParams(){
        return $this->_params;
    }
    
    public function getController(){
        return $this->_controller;
    }

    public function getAction(){
        return $this->_action;
    }
    
}

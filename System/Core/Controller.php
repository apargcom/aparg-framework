<?php

/**
 * Aparg Framework
 * 
 * @author Aparg
 * @link http://www.aparg.com/
 * @copyright Aparg
 */

namespace System\Core;

class Controller {
    
    private function __construct() { }
    
    public function __clone() { }

    public function __wakeup() { }
    
    public static $instance;
    
    private $controller;
    
    private $view;
    
    private $route = '';
    
    private $requestVars = [];
        
    public static function getInstance() {
        
        if (!isset(self::$instance)) {
            $className = __CLASS__;
            self::$instance = new $className;  
        }
        return self::$instance;
    }   
    
    public function init() {
        
        //self::$instance = self::getInstance();
        
        self::$instance->view = View::getInstance();
        
        $URI = $_SERVER['REQUEST_URI'];
        //Filter URI
        $URI = self::$instance->filterURI($URI); 
        //Route URI
        $URI = self::$instance->routeURI($URI); 
        //Parse URI
        $parsedURI = self::$instance->parseURI($URI);
        self::$instance->route = $parsedURI['route'];
        self::$instance->requestVars = $parsedURI['requestVars'];
        
        if(!self::$instance->load(self::$instance->route[0], self::$instance->route[1])){
            $parsedURI = self::$instance->parseURI(Config::get('route_404'));
            self::$instance->route = $parsedURI['route'];            
            self::$instance->load(self::$instance->route[0], self::$instance->route[1]);
        }
        //TODO: This part must be optimised
    }
    
    private function parseURI($URI){
        
        //Split URI        
        $splitURI = preg_split('/[\/]+/', $URI, null, PREG_SPLIT_NO_EMPTY);          
        
        $route[0] = strtolower((isset($splitURI[0]))?$splitURI[0]:Config::get('default_controller'));
        $route[1] = strtolower((isset($splitURI[1]))?$splitURI[1]:'index');       

        unset($splitURI[0]);
        unset($splitURI[1]);
        
        return [
            'route' => [$route[0], $route[1]],
            'requestVars' => $splitURI
        ];
    }
    
    private function filterURI($URI){
        
        return strtolower(trim(strtok($URI,'?'),'/')); 
    }
    
    private function routeURI($URI){
        
        $tmpURI = $URI;
        foreach(Config::get('routes') as $from => $to){
            $URI = preg_replace('/^' . preg_quote($from, '/') . '/', $to, $tmpURI);
        } 
        return $URI;
    }
    
    private function load($controller, $view){ 
        
       
        $tmpController = ucfirst($controller.'Controller');
        $tmpAction = $view.'Action';
        
        self::$instance->view->bufferStart();  //TODO: Buffering start/end must be optimised
                
        if(file_exists(Config::get('app_path').'/Controllers/'.$tmpController.'.php')){
            require_once Config::get('app_path').'/Controllers/'.$tmpController.'.php';            
            if(class_exists($tmpController, false)){                
                unset(self::$instance->controller);
                self::$instance->controller = new $tmpController();                 
                
                if(method_exists(self::$instance->controller, $tmpAction)){                    
                    self::$instance->controller->$tmpAction();
                    self::$instance->view->bufferFlush();   //TODO: Buffering start/end must be optimised 
                    return true;
                }
            }
        }
        return false;
    }
    
    protected function getRequestVars(){
        
        return  self::$instance->requestVars;
    }
    
    protected function view(){
        
        $tmpController = (func_num_args() == 2) ? func_get_arg(0) : self::$instance->route[0];
        $tmpAction = (func_num_args() == 2) ? func_get_arg(1) : ((func_num_args() == 1) ? func_get_arg(0) : self::$instance->route[1]);
         
        self::$instance->view->load($tmpController, $tmpAction);
    }
        
}

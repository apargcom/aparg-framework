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
    
    protected $controller;
    
    protected $route = [];
    
    protected $requestVars = [];
        
    public static function getInstance() {
        
        if (!isset(self::$instance)) {
            $className = __CLASS__;
            self::$instance = new $className;  
        }
        return self::$instance;
    }   
    
    public function init() {
        
        self::$instance = self::getInstance();
        
        $parsedURI = self::$instance->parseURI($_SERVER['REQUEST_URI']);              
        self::$instance->route = $parsedURI['route'];
        self::$instance->requestVars = $parsedURI['requestVars'];
        
        if(!self::$instance->load(self::$instance->route[0].'/'.self::$instance->route[0])){
            self::$instance->load(Config::get('route_404'));            
        }
    }
    
    private function parseURI($URI){
        
        //Filter URI
        $URI = strtolower(trim(strtok($URI,'?'),'/')); 
        //Route URI
        $tmpURI = $URI;
        foreach(Config::get('routes') as $from => $to){
            $URI = preg_replace('/^' . preg_quote($from, '/') . '/', $to, $tmpURI);
        }                
        //Split URI        
        $splitedURI = preg_split('/[\/]+/', $URI, null, PREG_SPLIT_NO_EMPTY);          
        
        $route[0] = strtolower((isset($splitedURI[0]))?$splitedURI[0]:Config::get('default_controller'));
        $route[1] = strtolower((isset($splitedURI[1]))?$splitedURI[1]:'index');       

        unset($splitedURI[0]);
        unset($splitedURI[1]);
        $requestVars = $splitedURI;
        return [
            'route' => [$route[0], $route[1]],
            'requestVars' => $requestVars
        ];
    }
    
    
    public function load($route = ''){ 
        
        self::$instance->route = self::$instance->parseURI($route)['route'];
        $tmpController = ucfirst(self::$instance->route[0].'Controller');
        $tmpAction = self::$instance->route[1].'Action';
        if(file_exists(Config::get('app_path').'/Controllers/'.$tmpController.'.php')){
            require_once Config::get('app_path').'/Controllers/'.$tmpController.'.php';            
            if(class_exists($tmpController, false)){                
                unset(self::$instance->controller);
                self::$instance->controller = new $tmpController();                 
                self::$instance->controller->parent = &self::$instance; //TODO: think of some better way to pass data to child controller
                if(method_exists(self::$instance->controller, $tmpAction)){                    
                    self::$instance->controller->$tmpAction();
                    return true;
                }
            }     
        }
        return false;
    }
        
}

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
    
    private static $instance;
    
    private $controller;
    
    private $route = [
        'controlelr'=>'',
        'action'=>''
        ];
    
    private $requestVars = [];
    
    public static function getInstance() {
        
        if (!isset(self::$instance)) {
            $className = __CLASS__;
            self::$instance = new $className;
        }
        return self::$instance;
    }   
    
    public function init() {
        
        self::$instance = self::getInstance();
        self::$instance->parseURI();
        self::$instance->runAction(self::$instance->route);
    }
    
    private function parseURI(){
        
        $URI = strtolower(trim(strtok($_SERVER['REQUEST_URI'],'?'),'/'));        
        $splitedURI = preg_split('/[\/]+/', $URI, null, PREG_SPLIT_NO_EMPTY);          
        
        $this->route['controller'] = ucfirst(strtolower((isset($splitedURI[0]))?$splitedURI[0]:Config::get('default_controller'))).'Controller';
        $this->route['action'] = strtolower((isset($splitedURI[1]))?$splitedURI[1]:'index').'Action';       
        
        unset($splitedURI[0]);
        unset($splitedURI[1]);
        self::$instance->requestVars = $splitedURI;
    }
    
    public function runAction($route = []){
        //TODO If Controller/Action not exsist or $route array is emptu think some logic where to go(maybe "404 not found" page)
        
        require_once Config::get('app_path').'/Controllers/'.$route['controller'].'.php';
        
        $controllerObj = new $route['controller']();        
        $controllerObj->$route['action']();
    }
        
}

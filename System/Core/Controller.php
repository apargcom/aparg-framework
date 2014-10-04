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
    
    private static $instance;
    private $controller;
    private $action;
    private function __construct() { }
    
    public static function getInstance() {
        if (!isset(self::$instance)) {
            $className = __CLASS__;
            self::$instance = new $className;
        }
        return self::$instance;
    }

    public function __clone() {
        //trigger_error('Clone is not allowed.', E_USER_ERROR);
    }

    public function __wakeup() {
        //trigger_error('Unserializing is not allowed.', E_USER_ERROR);
    }
    
    public function init() {
        $URL = strtolower(trim(strtok($_SERVER['REQUEST_URI'],'?'),'/'));        
        $splitedURL = preg_split('/[\/]+/', $URL, null, PREG_SPLIT_NO_EMPTY);          
        
        $controller = $this->controller = ucfirst(strtolower((isset($splitedURL[0]))?$splitedURL[0]:Config::get('default_controller'))).'Controller';
        $action = $this->action = strtolower((isset($splitedURL[1]))?$splitedURL[1]:'index').'Action';        
        //class_alias('System\Core\Controller', 'Controller');
        
        
        require_once Config::get('app_path').'/Controllers/'.$controller.'.php';
        
        $controllerObj = new $controller();        
        $controllerObj->$action();
    }
        
}

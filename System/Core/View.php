<?php

/**
 * Aparg Framework
 * 
 * @author Aparg
 * @link http://www.aparg.com/
 * @copyright Aparg
 */

namespace System\Core;

class View { //TODO: View class must be optimised
    
    public function __clone() { }

    public function __wakeup() { }
    
    public static $instance;
    
    public static function getInstance() {
        
        if (!isset(self::$instance)) {
            $className = __CLASS__;
            self::$instance = new $className;  
        }
        return self::$instance;
    }   
    
    public function init(){
        
        self::$instance->bufferStart();
    }
    
    private function bufferStart(){
        
        ob_start('\System\Core\View::bufferCallback');
        
    }
    
    public static function bufferCallback($buffer){
        return $buffer;
    }
    
    private static function bufferFlush(){
        
        ob_end_flush();
    }
    
    public function load($controller, $view){
        
        require (file_exists(Config::get('app_path').'/Views/'.$controller.'/'.$view.'.php')) ? Config::get('app_path').'/Views/'.$controller.'/'.$view.'.php' : '';
    }
}

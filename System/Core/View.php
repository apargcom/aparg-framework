<?php

/**
 * Aparg Framework
 * 
 * @author Aparg
 * @link http://www.aparg.com/
 * @copyright Aparg
 */

namespace System\Core;

class View extends Singleton{ 
    
    
    public static function init(){
        
        self::obj()->bufferStart();
    }    
    
    public function render(){
        self::obj()->bufferFlush();
    }
    
    private function bufferStart(){
       //'\System\Core\View::bufferCallback'
        if(Config::obj()->get('output_buffering')){
            ob_start(array(self::obj(),'bufferCallback'));
        }
    }

    private function bufferCallback($buffer){
        
        return $buffer;        
    }
    
    private  function bufferFlush(){
        if(Config::obj()->get('output_buffering')){
            ob_end_flush();
        }
    }
    
    public function load($route){
        
        $currentRoute = URI::obj()->route;
        $route = empty ($route) ? $currentRoute : $route;
        
        if(file_exists(Config::obj()->get('app_path').'/Views/'.$route.'.php')){
            require Config::obj()->get('app_path').'/Views/'.$route.'.php';
        }
    }
}

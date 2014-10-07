<?php

/**
 * Aparg Framework
 * 
 * @author Aparg
 * @link http://www.aparg.com/
 * @copyright Aparg
 */

namespace System\Core;

class Autoloader {
    
    private function __construct() {}
    
    public function __clone() { }

    public function __wakeup() { }

    public static function load($class) { 
        
        if(isset(Config::get('aliases')[$class])){           
            return class_alias('\\'.trim(Config::get('aliases')[$class],'\\'), $class);
        }
        self::loadClass($class);        
    }
    
    private static function loadClass($class){
        
        $packages = explode('\\', $class);
        
        $mainPackage = $packages[0]; 
        unset($packages[0]);
        
        $path = implode('/', $packages);
        if($mainPackage == 'System'){            
            $fileName = Config::get('system_path') . '/' . $path . ".php";            
        }else if($mainPackage == 'App'){
            $fileName = Config::get('app_path') . '/' . $path . ".php";
        }
       
        if (file_exists($fileName)){
            require_once $fileName;
        }
    }

}

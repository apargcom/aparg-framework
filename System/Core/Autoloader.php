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

    private static $fileName;

    public static function load($class) { 
        
        if(isset(Config::get('alias')[$class])){           
            return class_alias(Config::get('alias')[$class], $class);
        }
        self::loadClass($class);        
    }
    
    public static function loadClass($class){
        
        $packages = explode('\\', $class);
        
        $mainPackage = $packages[0]; 
        unset($packages[0]);
            
        $path = implode('/', $packages);
        if($mainPackage == 'System'){            
            self::$fileName = Config::get('system_path') . '/' . $path . ".php";            
        }else if($mainPackage == 'App'){
            self::$fileName = Config::get('app_path') . '/' . $path . ".php";
        }
       
        if (file_exists(self::$fileName)){
            require_once self::$fileName;
        }
    }

}

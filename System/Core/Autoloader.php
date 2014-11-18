<?php

/**
 * Aparg Framework
 * 
 * @author Aparg
 * @link http://www.aparg.com/
 * @copyright Aparg
 */

namespace System\Core;

//Preload classes
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Singleton.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Config.php';

class Autoloader extends Singleton{
    
    public static function load(){
        if(self::isObj()){
            return self::obj();            
        }        
        spl_autoload_register(array(self::obj(),'autoLoad'));
        return self::obj();
    }
            
    public function autoLoad($class) {
        
        $aliases = Config::obj()->get('aliases');
        if(isset($aliases[$class])){          
            return class_alias('\\'.trim(Config::obj()->get('aliases')[$class],'\\'), $class);
        }
        $this->loadClass($class);        
    }
    
    
    private function loadClass($class){
        
        $packages = explode('\\', $class);
        
        $mainPackage = $packages[0]; 
        unset($packages[0]);
        
        $path = implode(DIRECTORY_SEPARATOR, $packages);
        if($mainPackage == 'System'){            
            $fileName = Config::obj()->get('system_path') . DIRECTORY_SEPARATOR . $path . ".php";            
        }else if($mainPackage == 'App'){
            $fileName = Config::obj()->get('app_path') . DIRECTORY_SEPARATOR . $path . ".php";
        }
       
        if (file_exists($fileName)){
            require_once $fileName;
        }
    }
}

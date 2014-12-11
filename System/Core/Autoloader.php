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
    
    private $aliases = null;
    private $systemPath = null;
    private $appPath = null;
    
    public function init(){
        
        $this->aliases = Config::obj()->get('aliases');        
        $this->systemPath = Config::obj()->get('system_path');
        $this->appPath = Config::obj()->get('app_path');
        spl_autoload_register(array($this,'load'));
        return true;
    }
            
    public function load($class) {
                
        if(isset($this->aliases[$class])){          
            class_alias('\\'.trim($this->aliases[$class],'\\'), $class);
            return true;
        }
        $this->loadClass($class);        
    }
    
    
    private function loadClass($class){
        
        $packages = explode('\\', $class);
        
        $mainPackage = $packages[0]; 
        unset($packages[0]);
        
        $path = implode(DIRECTORY_SEPARATOR, $packages);
        if($mainPackage == 'System'){            
            $fileName = $this->systemPath . DIRECTORY_SEPARATOR . $path . ".php";            
        }else if($mainPackage == 'App'){
            $fileName = $this->appPath . DIRECTORY_SEPARATOR . $path . ".php";
        }
       
        if (file_exists($fileName)){
            require_once $fileName;
        }
    }
}


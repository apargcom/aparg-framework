<?php
namespace System\Core;

class Config{
    
    private function __construct() { }
    
    public function __clone() { }

    public function __wakeup() { }
    
    private static $instance;
    
    private $config;
    
    public static function getInstance() {
        
        if (!isset(self::$instance)) {
            $className = __CLASS__;
            self::$instance = new $className;
        }
        return self::$instance;
    }
    
    public function init($array){
        
        self::$instance = self::getInstance();
        self::$instance->config = self::defaults();        
        self::set($array);        
        return true;
    }
    
    private static function defaults(){
        
        return require_once __DIR__.'/../config.php';
    }    
    
    public static function get($key = ''){
        
        if(isset(self::$instance->config[$key])){
            return self::$instance->config[$key];
        }else{
            return false;
        }
    }
    public static function set($value = []){
        
        self::$instance->config = array_replace_recursive(self::$instance->config,  $value);
        if(self::$instance->config != null){
            return true;
        }else{
            return false;
        }
    }
}


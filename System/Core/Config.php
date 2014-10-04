<?php
namespace System\Core;
class Config{
    private static $config;
    
    private static function defaults(){
        
        return require_once __DIR__.'/../config.php';
    }
    public static function init($array){
        self::$config = self::defaults();
        self::set($array);        
        return true;
    }
    public static function get($key){
        if(isset(self::$config[$key])){
            return self::$config[$key];
        }else{
            return false;
        }
    }
    public static function set($array){
        self::$config = array_replace_recursive(self::$config,  $array);
        if(self::$config != null){
            return true;
        }else{
            return false;
        }
    }
}


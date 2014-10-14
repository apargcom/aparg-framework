<?php

/**
 * Aparg Framework
 * 
 * @author Aparg
 * @link http://www.aparg.com/
 * @copyright Aparg
 */

namespace System\Core;

class Config extends Singleton{
    
    private $config;
    
    public static function init($array){
        
                
        self::instance()->config = self::defaults();  
        self::set($array);        
        return true;
    }
    
    
    
    private static function defaults(){
        
        return require_once __DIR__.'/../config.php';
    }    
    
    public static function get($key = ''){
        
        if(isset(self::instance()->config[$key])){
            return self::instance()->config[$key];
        }else{
            return false;
        }
    }
    public static function set($value = []){
        
        self::instance()->config = array_replace_recursive(self::instance()->config,  $value);
        if(self::instance()->config != null){
            return true;
        }else{
            return false;
        }
    }
}


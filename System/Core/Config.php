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
    
    
    public static function load($array){
        if(self::isObj()){
            return self::obj();            
        }         
        self::obj()->config = self::obj()->defaults();  
        self::obj()->set($array);        
        return self::obj();
    }
    
    private function defaults(){
        
        return require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'config.php';
    }    
    
    public function get($key = ''){
        
        if(isset($this->config[$key])){
            return $this->config[$key];
        }else{
            return false;
        }
    }
    public function set($value = []){
        
        $this->config = array_replace_recursive($this->config,  $value);
        if($this->config != null){
            return true;
        }else{
            return false;
        }
    }
}


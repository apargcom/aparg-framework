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
    
    
    public function init($array){
//        if(self::isObj()){
//            return self::obj();            
//        }         
        $this->config = $this->defaults();  
        $this->set($array);  
        return true;
//        return self::obj();
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
    public function set($value = []){//TODO: Think if we need to set config value or it's safer not to allow this
        
        $this->config = array_replace_recursive($this->config,  $value);
        if($this->config != null){
            return true;
        }else{
            return false;
        }
    }
}


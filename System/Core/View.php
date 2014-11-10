<?php

/**
 * Aparg Framework
 * 
 * @author Aparg
 * @link http://www.aparg.com/
 * @copyright Aparg
 */

namespace System\Core;

use \App;

class View extends App{ 
    
    private $data = [];
    
    public function __construct(){
        
        parent::__construct();
        
        $this->bufferStart();
    }    
    
    public function render(){
        $this->bufferFlush();
    }
    
    private function bufferStart(){
       //'\System\Core\View::bufferCallback'
        if($this->config->get('output_buffering')){
            ob_start(array($this,'bufferCallback'));
        }
    }

    private function bufferCallback($buffer){
        
        return $buffer;        
    }
    
    private  function bufferFlush(){
        if($this->config->get('output_buffering')){
            ob_end_flush();
        }
    }
    
    public function load($route = '', $data = [], $return = false){ //TODO: Think if we need integrate Cache module with View to automatically write and load form cache
                
        $this->data = $data;
        
        $route = empty ($route) ? $this->URI->route : $route;
        
        if(file_exists($this->config->get('app_path') . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR . $route . '.php')){
            if($return){
                ob_start();
            }                        
            require $this->config->get('app_path') . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR . $route . '.php';
            if($return){
                return ob_get_clean();                                
            }  
            return true;
        }else{
            return false;
        }
    }
    
    public function __set($name, $value){
        
        $this->data[$name] = $value;
    }
    public function __get($name){
        
        return isset($this->data[$name]) ? $this->data[$name] : null;
    }
}

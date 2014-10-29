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
                
        $route = empty ($route) ? $this->URI->route : $route;
        
        if(file_exists($this->config->get('app_path').'/Views/'.$route.'.php')){
            if($return){
                ob_start();
            }                        
            require $this->config->get('app_path').'/Views/'.$route.'.php';
            if($return){
                return ob_get_clean();                                
            }  
            return true;
        }else{
            return false;
        }
    }
}

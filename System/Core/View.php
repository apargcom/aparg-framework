<?php

namespace System\Core;

/**
 * Aparg Framework {@link http://www.aparg.com}
 * 
 * View class is for rendering view files
 * 
 * @author Aparg <info@aparg.com>
 * @copyright Aparg
 * @package System
 * @subpackage Core
 */
class View extends Singleton{ 
    
    /**
     * @var array Contains variables that were passed from controller
     */
    private $data = [];
    /**
     * @var boolean Enable/disable output buffering
     */
    private $outputBuffering = true;
    /**
     * @var string Path to application folder
     */
    private $appPath = '';
    
    /**
     * Initialize the view
     *      
     * @return void
     */
    public function init(){
        
        $this->outputBuffering = Config::obj()->get('output_buffering');
        $this->appPath = Config::obj()->get('app_path');
        $this->bufferStart();
    }
    
    /**
     * Render all loaded view files
     * 
     * @return void
     */
    public function render(){
        
        $this->bufferFlush();
    }
    
    /**
     * Starts buffering
     * 
     * @return void
     */
    private function bufferStart(){
       
        if($this->outputBuffering){
            ob_start(array($this,'bufferCallback'));
        }
    }

    /**
     * Buffer callback method. Being called when buffer is flushed
     * 
     * @param string $buffer Current data that is in buffer
     * @return string Data to be flushed
     */
    private function bufferCallback($buffer){
        
        return $buffer;        
    }
    
    /**
     * Flush the buffer
     * 
     * @return void
     */
    private  function bufferFlush(){
        if($this->outputBuffering){
            ob_end_flush();
        }
    }
    
    /**
     * Loads view file for further render
     * 
     * @param string $route Route of view file to load
     * @param array $data Array with data to be passed to view file
     * @param boolean $return Whether to flush or return rendered view
     * @return string|boolean True or rendered data(if $return=true) on success, false on fail
     */
    public function load($route = '', $data = [], $return = false){ 
                
        $this->data = $data;        
        $route = strtolower(($route == '') ? URI::obj()->route : $route);        
        
        if(file_exists($this->appPath . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR . $route . '.php')){
            if($return){
                ob_start();
            }                        
            require $this->appPath . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR . $route . '.php';
            if($return){
                return ob_get_clean();                                
            }  
            return true;
        }else{
            return false;
        }
    }
    
    /**
     * Magic method for setting variable in view files Ex.:$this->variableName = 'variable_value'
     * 
     * @param string $name Name of the variable
     * @param string $value Value of the variable
     */
    public function __set($name, $value){
        
        $this->data[$name] = $value;
    }
    
    /**
     * Magic method for getting variable in view files Ex.:$this->variableName
     * 
     * @param string $name Name of the variable
     */
    public function __get($name){
        
        return isset($this->data[$name]) ? $this->data[$name] : null;
    }
}
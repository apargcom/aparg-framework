<?php

/**
 * Aparg Framework
 * 
 * @author Aparg
 * @link http://www.aparg.com/
 * @copyright Aparg
 */

namespace System\Core;

class URI {
    
    public $URI = '';
        
    public $route = '';
    
    public $vars = [];
    
    public function __construct($URI){
        
        $this->URI = $URI;
        $this->filter();
    }
    
    private function filter(){
        
        $this->URI = strtolower(trim(strtok($this->URI,'?'),'/')); 
    }  
    
    public function parse(){
                
        $splitURI = preg_split('/[\/]+/', $this->URI, null, PREG_SPLIT_NO_EMPTY);          
        
        $route[0] = strtolower(isset($splitURI[0])?$splitURI[0]:Config::get('default_controller'));
        $route[1] = strtolower(isset($splitURI[1])?$splitURI[1]:'index');       

        unset($splitURI[0]);
        unset($splitURI[1]);
        
        $this->route = $route[0].'/'.$route[1];
        $this->vars = $splitURI;        
    }
    
    public function route($routes = []){
        
        $routes = empty($routes) ? Config::get('routes') : $routes;
        $URI = $this->URI;
        foreach($routes as $from => $to){
            $URI = preg_replace('/^' . preg_quote($from, '/') . '/', $to, $URI);
        } 
        $this->URI = $URI;
    }
}

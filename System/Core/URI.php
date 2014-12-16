<?php

/**
 * Aparg Framework
 * 
 * @author Aparg
 * @link http://www.aparg.com/
 * @copyright Aparg
 */

namespace System\Core;

class URI extends Singleton{
        
    public $URI = '';        
    public $route = '';    
    public $vars = [];    
    public $lang = '';
    
    private $defaultLanguage = '';
    private $languages = [];
    private $defaultController = '';
    private $routes = [];
    
    public function init($URI = ''){
        
        $this->languages = Config::obj()->get('languages');
        $this->defaultLanguage = Config::obj()->get('default_language');
        $this->defaultController = Config::obj()->get('default_controller');
        $this->routes = Config::obj()->get('routes');
        $this->URI = $URI;
        
        $this->filter();
        $this->route();
        $this->parse();
    }
    

    private function filter(){
        
        $this->URI = trim(strtok($this->URI,'?'),'/'); 
    }  
    
    private function parse(){
                
        $splitURI = preg_split('/[\/]+/', $this->URI, null, PREG_SPLIT_NO_EMPTY);          
        
        $langI = 0;
        $controllerI = 0;
        $actionI = 1;
        $this->lang = $this->defaultLanguage;
        if(isset($splitURI[0]) && array_search($splitURI[0], $this->languages) !== false){
            $this->lang = $splitURI[0]; 
            $controllerI = 1;
            $actionI = 2;             
        }
           
        $route[0] = isset($splitURI[$controllerI])?$splitURI[$controllerI]:$this->defaultController;
        $route[1] = isset($splitURI[$actionI])?$splitURI[$actionI]:'index';      

        unset($splitURI[$langI]);
        unset($splitURI[$controllerI]);
        unset($splitURI[$actionI]);
        
        $this->route = $route[0].'/'.$route[1];
        $this->vars = [
            'URI' => array_values($splitURI),
            'GET' => $_GET,
            'POST' => $_POST
            ];
    }
    
    private function route($routes = []){
        
        $routes = empty($routes) ? $this->routes : $routes;
        $URI = $this->URI;
        foreach($routes as $from => $to){
            $URI = preg_replace('/^' . preg_quote($from, '/') . '/', $to, $URI);
        } 
        $this->URI = $URI;
    }
    
    public function redirect($URL, $code = 302){  
                                                    
        header('Location: ' . $URL, true, $code);
    }
}

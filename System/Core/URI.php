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
    

    public static function init($URI = ''){
        
        self::obj()->URI = $URI;
        self::obj()->filter();
        self::obj()->route();
        self::obj()->parse();
    }
    

    private function filter(){
        
        $this->URI = strtolower(trim(strtok($this->URI,'?'),'/')); 
    }  
    
    private function parse(){
                
        $splitURI = preg_split('/[\/]+/', $this->URI, null, PREG_SPLIT_NO_EMPTY);          
        
        $route[0] = strtolower(isset($splitURI[0])?$splitURI[0]:Config::obj()->get('default_controller'));
        $route[1] = strtolower(isset($splitURI[1])?$splitURI[1]:'index');       

        unset($splitURI[0]);
        unset($splitURI[1]);
        
        $this->route = $route[0].'/'.$route[1];
        $this->vars = [
            'URI' => array_values($splitURI),
            'GET' => $_GET,
            'POST' => $_POST
            ];
    }
    
    private function route($routes = []){
        
        $routes = empty($routes) ? Config::obj()->get('routes') : $routes;
        $URI = $this->URI;
        foreach($routes as $from => $to){
            $URI = preg_replace('/^' . preg_quote($from, '/') . '/', $to, $URI);
        } 
        $this->URI = $URI;
    }
    
     public function redirect($URL, $code = 302){ //TODO: Move to URL class
                                                    //TODO: Maybe better set URL class instance and call $this->URL->redirect() from child controller                    
                                                    
        header('Location: ' . $URL, true, $code);
    }
    
}

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

    public static function init($URI = ''){
        if(self::isObj()){
            return self::obj();            
        }
        self::obj()->URI = $URI;
        self::obj()->filter();
        self::obj()->route();
        self::obj()->parse();
        return self::obj();
    }
    

    private function filter(){
        
        $this->URI = strtolower(trim(strtok($this->URI,'?'),'/')); 
    }  
    
    private function parse(){
                
        $splitURI = preg_split('/[\/]+/', $this->URI, null, PREG_SPLIT_NO_EMPTY);          
        
        $langI = 0;
        $controllerI = 0;
        $actionI = 1;
        $this->lang = Config::obj()->get('default_lang');
        if(isset($splitURI[0]) && array_search($splitURI[0], Config::obj()->get('lang')) !== false){
            $this->lang = $splitURI[0]; 
            $controllerI = 1;
            $actionI = 2;             
        }
           
        $route[0] = strtolower(isset($splitURI[$controllerI])?$splitURI[$controllerI]:Config::obj()->get('default_controller'));
        $route[1] = strtolower(isset($splitURI[$actionI])?$splitURI[$actionI]:'index');      

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

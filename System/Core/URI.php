<?php

namespace System\Core;

/**
 * Aparg Framework {@link http://www.aparg.com}
 * 
 * URI class is working with URIs
 *
 * @version 1.0
 * @author Aparg <info@aparg.com>
 * @copyright Aparg
 * @package System
 * @subpackage Core
 */
class URI extends Singleton{
        
    /**
     * @var string Current URI
     */
    public $URI = '';            
    /**
     * @var string Current route
     */
    public $route = '';            
    /**
     * @var array Array with GET, POST and URI variables
     */
    public $vars = [];            
    /**
     * @var string Current language
     */
    public $lang = '';        
    /**
     * @var string Default language
     */
    private $defaultLanguage = '';        
    /**
     * @var array Array with available language
     */
    private $languages = [];        
    /**
     * @var string Default controller 
     */
    private $defaultController = '';        
    /**
     * @var array Array with routes that will be replace in current URI
     */
    private $routes = [];
    
    /**
     * Initialize URI
     * 
     * @param string $URI URI to work with
     * @return void
     */
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
    
    /**
     * Filter current URI
     * 
     * @return void
     */
    private function filter(){
        
        $this->URI = trim(strtok($this->URI,'?'),'/'); 
    }  
    
    /**
     * Parse current URI. Grab route and variables
     * 
     * @return void
     */
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
    
    /**
     * Replace all matched routes in current URI with $routes array
     * 
     * @param array $routes Array with routes, if empty local $routes array is used
     * @see $routes
     */
    private function route($routes = []){
        
        $routes = empty($routes) ? $this->routes : $routes;
        $URI = $this->URI;
        foreach($routes as $from => $to){
            $URI = preg_replace('/^' . preg_quote($from, '/') . '/', $to, $URI);
        } 
        $this->URI = $URI;
    }
    
    /**
     * Set Location header to redirect by given URL
     * 
     * @param string $URL URL to redirect
     * @param integer $code Status code to send with headers
     * @return void
     */
    public function redirect($URL, $code = 302){  
                                                    
        header('Location: ' . $URL, true, $code);
    }
}
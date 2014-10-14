<?php

/**
 * Aparg Framework
 * 
 * @author Aparg
 * @link http://www.aparg.com/
 * @copyright Aparg
 */

use \System\Core\Config;
use \System\Core\Controller;
use \System\Core\URI;
use \System\Core\Autoloader;
use \System\Core\Singleton;

require_once '/Core/Autoloader.php';
Autoloader::init();

class App extends Singleton{
    
    
    private $URI;
    
    private $controller;
    
    public static function start1(){
        echo 'dc';
    }
    public static function start($config = []){ 
         
        self::instance()->init($config);
    }
    
    private function init($config = []){      
        
               
        Config::init($config);  
        //Setup autoload
        
        
        //Check compatibility        
        if(phpversion() < Config::get('min_php_version')){ 
            trigger_error('Suported PHP version is 5.3.3 and above.', E_USER_ERROR);                    
        }
 
        //Check debug mode
        error_reporting((Config::get('debug_mode')) ? -1 : 0);
        
        
        
        //Start main controller
        $this->URI = new URI($_SERVER['REQUEST_URI']);  
        $this->URI->route();
        $this->URI->parse();
        //$this->loadAction();
        Controller::load($this->URI);
    }
    
//   
    
}
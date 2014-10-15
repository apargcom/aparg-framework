<?php

/**
 * Aparg Framework
 * 
 * @author Aparg
 * @link http://www.aparg.com/
 * @copyright Aparg
 */

use \System\Core\Autoloader;
use \System\Core\Config;
use \System\Core\Controller;


class App{
    
    
    public static function start($config = []){ 
         
        self::init($config);
    }
    
    private static function init($config = []){      
        
        //Init autoload
        require_once __DIR__ . '/Core/Autoloader.php';
        Autoloader::init();
        
        //Init config
        Config::init($config);  
        
        //Check compatibility        
        if(phpversion() < Config::obj()->get('min_php_version')){ 
            trigger_error('Suported PHP version is 5.3.3 and above.', E_USER_ERROR);                    
        }
 
        //Check debug mode
        error_reporting((Config::obj()->get('debug_mode')) ? -1 : 0);
        
        Controller::init();
    }
}
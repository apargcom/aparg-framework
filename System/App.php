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

class App{
    
    private function __construct() { }
    
    public function __clone() { }

    public function __wakeup() { }
    
    private static $instance;
    
    private $URI;
    
    private $controller;
    
    private static function getInstance() {//TODO: understand if getInstance will be public or not
        
        if (!isset(self::$instance)) {
            $className = __CLASS__;
            self::$instance = new $className;
        }
        return self::$instance;
    }   
    
    public static function start($config = []){        
        if(isset(self::$instance)) {
            return false;
        }
        self::$instance = self::getInstance();
        self::$instance->init($config);
    }
    
    private function init($config = []){
        
        //Setup configs
        require_once __DIR__.'/Core/Config.php';
        Config::init($config);  
        
        //Check compatibility        
        if(phpversion() < Config::get('min_php_version')){ 
            trigger_error('Suported PHP version is 5.3.3 and above.', E_USER_ERROR);                    
        }
 
        //Check debug mode
        error_reporting((Config::get('debug_mode')) ? -1 : 0);
        
        //Setup autoload
        require_once Config::get('system_path').'/Core/Autoloader.php';
        spl_autoload_register('\System\Core\Autoloader::load');
        
        //Start main controller
        $this->URI = new URI($_SERVER['REQUEST_URI']);
        $this->URI->route();        
        $this->URI->parse();
        $this->loadAction();
    }
    
    private function loadAction(){
        
        $route = explode('/', $this->URI->route);
        
        $tmpController = ucfirst($route[0].'Controller');
        $tmpAction = $route[1].'Action';
        
        //self::$instance->view->bufferStart();  //TODO: Buffering start/end must be optimised
                
        if(file_exists(Config::get('app_path').'/Controllers/'.$tmpController.'.php')){
            require_once Config::get('app_path').'/Controllers/'.$tmpController.'.php';            
            if(class_exists($tmpController, false)){                
                //unset(self::$instance->controller);
                $this->controller = new $tmpController(self::getInstance());   
                if(method_exists($this->controller, $tmpAction)){                    
                    $this->controller->$tmpAction($this->URI->vars);
                //    self::$instance->view->bufferFlush();   //TODO: Buffering start/end must be optimised 
                    return true;
                }
            }
        }
        return false;
    }
    
}
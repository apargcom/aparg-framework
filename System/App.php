<?php
use System\Core\Config;
use System\Core\Alias;

class App{
    
    private function __construct() {}
    
    public function __clone() { }

    public function __wakeup() { }
    
    private static $instance;
    
    public static function getInstance() {
        
        if (!isset(self::$instance)) {
            $className = __CLASS__;
            self::$instance = new $className;
        }
        return self::$instance;
    }   
    
    public static function start($config = []){        
        
        self::$instance = self::getInstance();
        self::$instance->init($config);
    }
    
    private function init($config = []){
        
        //Setup configs
        require_once __DIR__.'/Core/Config.php';
        Config::getInstance()->init($config);  
        
        //Check compatibility        
        if(phpversion() < Config::get('min_php_version')){ 
            trigger_error('Suported PHP version is 5.3.3 and above.', E_USER_ERROR);                    
        }
 
        //Check debug mode
        error_reporting((Config::get('debug_mode')) ? -1 : 0);
        
        //Setup autoload
        require_once Config::get('system_path').'/Core/Autoloader.php';
        spl_autoload_register('System\Core\Autoloader::load');
        
        //Start main controller
        System\Core\Controller::getInstance()->init();
    }
}
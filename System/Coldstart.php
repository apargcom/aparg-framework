<?php
use System\Core\Config;
use System\Core\Alias;
class Coldstart{
    private function __construct(){}
    public static function config($config){
        require_once __DIR__.'/Core/Config.php';
        Config::init($config);  
        self::init();
    }
    private static function init(){
        
        //Check compatibility        
        if(phpversion() < Config::get('min_php_version')){ 
            trigger_error('Suported PHP version is 5.3.3 and above.', E_USER_ERROR);                    
        }
 
        //Check debug mode
        error_reporting((Config::get('debug_mode')) ? -1 : 0);
        
        //Setup autoload
        require_once Config::get('system_path').'/Core/Autoloader.php';
        spl_autoload_register('System\Core\Autoloader::load');
        
        //Start class routing
        //Alias::init();
        
        //Start main controller
        $controller = System\Core\Controller::getInstance();
        $controller->init();
        
    }
}
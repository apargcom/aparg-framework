<?php

/**
 * Aparg Framework
 * 
 * @author Aparg
 * @link http://www.aparg.com/
 * @copyright Aparg
 */

namespace System\Core;


require_once __DIR__ . DIRECTORY_SEPARATOR . 'Singleton.php';

class App extends Singleton{
    
    public $conroller = null;
    
    
    private $logsPath = null;
    private $enableLogs = true;
    private $appPath = null;
    private $route404 = null;
    
    public function init($config = []){        
        
        require_once __DIR__ . DIRECTORY_SEPARATOR . 'Config.php';
        Config::obj()->init($config);          
        
        require_once __DIR__ .  DIRECTORY_SEPARATOR . 'Autoloader.php';
        Autoloader::obj()->init();
        
        $this->logsPath = Config::obj()->get('logs_path');
        $this->enableLogs = Config::obj()->get('enable_logs');
        $this->appPath = Config::obj()->get('app_path');
        $this->route404 = Config::obj()->get('route_404');
        
        if(phpversion() < Config::obj()->get('min_php_version')){ 
            trigger_error('Suported PHP version is 5.3.3 and above.', E_USER_ERROR);                    
        }
 
        error_reporting((Config::obj()->get('show_errors')) ? -1 : 0);
        
        URI::obj()->init($_SERVER['REQUEST_URI']);                                
                
        View::obj()->init();
        $this->controller = $this->loadController(URI::obj()->route, URI::obj()->vars);
        if($this->controller != false){
            View::obj()->render();
        }
    }
    
    public function log($type, $message){
                
        if($this->logsPath){            
            $log = '(' . date("Y-m-d H:i:s") . ') ' . $type . ': ' .  $message;                    
            return (file_put_contents($this->appPath, $log . PHP_EOL, FILE_APPEND) == false) ? false : true;
        }else{
            return false;
        }
    }
    
    private function loadController($route, $vars) {

        $splitRoute = explode('/', $route);

        $tmpController = ucfirst($splitRoute[0] . 'Controller');
        $tmpAction = $splitRoute[1] . 'Action';

        if (file_exists($this->appPath . DIRECTORY_SEPARATOR . 'Controllers' . DIRECTORY_SEPARATOR . $tmpController . '.php')) {
            require_once $this->appPath . DIRECTORY_SEPARATOR . 'Controllers' . DIRECTORY_SEPARATOR . $tmpController . '.php';
            $tmpController = '\\' . $tmpController;
            if (class_exists($tmpController, false)) {
                $controller = new $tmpController();
                if (method_exists($controller, $tmpAction)) {
                    $controller->$tmpAction($vars);                    
                    return $controller;
                }
            }
        }
        
        if ($route != $this->route404) {
            $load_404 = $this->loadController($this->route404, $vars);
            if ($load_404 !== false) {
                http_response_code(404);
                return $load_404;
            }
        }
        return false;
    }
    
    public function loadModel($name){ 
    
        $name = ucfirst($name);       
        $class = '\App\Models\\' . $name;        
       
        if(class_exists($class)){ 
            $classObj = new $class();                                
            return $classObj;            
        }         
        return false;       
    }
    
    public function loadModule($name, $system = true) {

        $name = ucfirst($name);
        $class = '\\' . ($system ? 'System' : 'App') . '\Modules\\' . $name;

        if (class_exists($class)) {
            $classObj = new $class();
            return $classObj;
        }
        if ($system)
            return $this->module($name, false);
        return false;
    }
}

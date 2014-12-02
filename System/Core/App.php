<?php

/**
 * Aparg Framework
 * 
 * @author Aparg
 * @link http://www.aparg.com/
 * @copyright Aparg
 */

namespace System\Core;

require_once __DIR__ .  DIRECTORY_SEPARATOR . 'Autoloader.php';
Autoloader::obj()->init();

class App extends Singleton{ //TODO: change App class to singleton and create it's object on start 
    
    public $conroller = null;

    public function init($config = []){
        
        
        Config::obj()->init($config);          
             
        if(phpversion() < Config::obj()->get('min_php_version')){ 
            trigger_error('Suported PHP version is 5.3.3 and above.', E_USER_ERROR);                    
        }
 
        error_reporting((Config::obj()->get('debug_mode')) ? -1 : 0);
        
        $this->URI = URI::obj();  
        $this->view = View::obj();
                       
        URI::obj()->init($_SERVER['REQUEST_URI']);                                
                
        $this->view->init();
        $this->controller = $this->loadController(URI::obj()->route, URI::obj()->vars);
        if($this->controller != false){
            $this->view->render();
        }
    }
    
    private function loadController($route, $vars) {

        $splitRoute = explode('/', $route);

        $tmpController = '\\' . ucfirst($splitRoute[0] . 'Controller');
        $tmpAction = $splitRoute[1] . 'Action';

        if (file_exists(Config::obj()->get('app_path') . DIRECTORY_SEPARATOR . 'Controllers' . DIRECTORY_SEPARATOR . $tmpController . '.php')) {
            require_once Config::obj()->get('app_path') . DIRECTORY_SEPARATOR . 'Controllers' . DIRECTORY_SEPARATOR . $tmpController . '.php';
            if (class_exists($tmpController, false)) {
                $controller = new $tmpController();
                if (method_exists($controller, $tmpAction)) {
                    $controller->$tmpAction($vars);                    
                    return $controller;
                }
            }
        }
        $route_404 = Config::obj()->get('route_404');
        if ($route != $route_404) {
            $load_404 = $this->loadController($route_404, $vars);
            if ($load_404 !== false) {
                http_response_code(404);
                return $load_404;
            }
        }
        return false;
    }
    
    public function loadModel($name){ 
    
        $name = ucfirst($name);
       // $path = ($system ? Config::obj()->get('system_path') : Config::obj()->get('app_path')) . '/Modules/' . $name . '.php';
        $class = '\App\Models\\' . $name;        
       
        if(class_exists($class)){ 
            $classObj = new $class();                                
            return $classObj;            
        }         
        return false;       
    }
    
    public function loadModule($name, $system = true) {

        $name = ucfirst($name);
        // $path = ($system ? Config::obj()->get('system_path') : Config::obj()->get('app_path')) . '/Modules/' . $name . '.php';
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
<?php

/**
 * Aparg Framework
 * 
 * @author Aparg
 * @link http://www.aparg.com/
 * @copyright Aparg
 */

namespace System\Core;

abstract class Controller {
    

    public static function init(){
        
        View::init();
        URI::init($_SERVER['REQUEST_URI']);
                                
        self::load(URI::obj()->route, URI::obj()->vars);
            
        View::obj()->render();
    }
    
    private static function load($route, $vars){
        
        $splitRoute = explode('/', $route);
        
        $tmpController = ucfirst($splitRoute[0].'Controller');
        $tmpAction = $splitRoute[1].'Action';
        
        if(file_exists(Config::obj()->get('app_path').'/Controllers/'.$tmpController.'.php')){
            require_once Config::obj()->get('app_path').'/Controllers/'.$tmpController.'.php';            
            if(class_exists($tmpController, false)){                
                //unset(self::$instance->controller);
                $controller = new $tmpController();   
                if(method_exists($controller, $tmpAction)){                    
                    $controller->$tmpAction($vars);                
                    return true;
                }
            }
        }
        $route_404 = Config::obj()->get('route_404');
        if($route != $route_404){
            if(self::load($route_404, URI::obj()->vars)){
                http_response_code(404); 
            }
        }
        return false;
    }
    
    protected function view($route = '', $data = [], $get = false){ //TODO: Maybe better set View class instance and call $this->view->load() from child controller
        
        return View::obj()->load($route, $data, $get);
    }
    
    protected function redirect($URL, $code = 302){ //TODO: Maybe better set URL class instance and call $this->URL->redirect() from child controller                    
        
        URL::obj()->redirect($URL, $code);
    }

    protected function module($name, $system = true){ 
    
        $name = ucfirst($name);
        $path = ($system ? Config::obj()->get('system_path') : Config::obj()->get('app_path')) . '/Modules/' . $name . '.php';
        $class = '\\' . ($system ? 'System': 'App') . '\Modules\\' . $name;
        if(file_exists($path)){  
            require_once $path;            
                if(class_exists($class, false)){  
                    return new $class();
                }  
        }
        if($system)
            $this->module($name, false);
        return false;       
    }

}

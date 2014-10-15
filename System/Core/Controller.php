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
        $route = explode('/', URI::obj()->route);
        
        $tmpController = ucfirst($route[0].'Controller');
        $tmpAction = $route[1].'Action';
        
        if(!self::load(URI::obj()->route, URI::obj()->vars)){
            
            self::load(Config::obj()->get('route_404'), URI::obj()->vars);
        }   
        View::obj()->render();
    }
    
    private static function load($route, $vars){
        
        $route = explode('/', $route);
        
        $tmpController = ucfirst($route[0].'Controller');
        $tmpAction = $route[1].'Action';
        
        //self::$instance->view->bufferStart();  //TODO: Buffering start/end must be optimised
                
        if(file_exists(Config::obj()->get('app_path').'/Controllers/'.$tmpController.'.php')){
            require_once Config::obj()->get('app_path').'/Controllers/'.$tmpController.'.php';            
            if(class_exists($tmpController, false)){                
                //unset(self::$instance->controller);
                $controller = new $tmpController();   
                if(method_exists($controller, $tmpAction)){                    
                    $controller->$tmpAction($vars);
                //    self::$instance->view->bufferFlush();   //TODO: Buffering start/end must be optimised 
                    return true;
                }
            }
        }
        
        return false;
    }
    
    protected function view($route = ''){ //TODO: Maybe better set View class instance and call $this->view->load() from child controller
        
        View::obj()->load($route);
    }
    
    protected function redirect($URL, $code = 303){ //TODO: Maybe better set View class instance and call $this->view->load() from child controller
                                                    //TODO: Choose best redirect status code
        View::obj()->redirect($URL, $code);
    }
}

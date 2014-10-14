<?php

/**
 * Aparg Framework
 * 
 * @author Aparg
 * @link http://www.aparg.com/
 * @copyright Aparg
 */

namespace System\Core;

class Controller {
    

    public static function load($URI){
        
        $route = explode('/', $URI->route);
        
        $tmpController = ucfirst($route[0].'Controller');
        $tmpAction = $route[1].'Action';
        
        //self::$instance->view->bufferStart();  //TODO: Buffering start/end must be optimised
                
        if(file_exists(Config::get('app_path').'/Controllers/'.$tmpController.'.php')){
            require_once Config::get('app_path').'/Controllers/'.$tmpController.'.php';            
            if(class_exists($tmpController, false)){                
                //unset(self::$instance->controller);
                $controller = new $tmpController();   
                if(method_exists($controller, $tmpAction)){                    
                    $controller->$tmpAction($URI->vars);
                //    self::$instance->view->bufferFlush();   //TODO: Buffering start/end must be optimised 
                    return true;
                }
            }
        }
        return false;
    }
    
}

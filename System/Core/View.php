<?php

/**
 * Aparg Framework
 * 
 * @author Aparg
 * @link http://www.aparg.com/
 * @copyright Aparg
 */

namespace System\Core;

class View extends Singleton{ //TODO: View class must be optimised
    
    private $redirect = [];
    
    public static function init(){
        
        self::obj()->bufferStart();
    }    
    
    public function render(){
        self::obj()->bufferFlush();
    }
    
    public function redirect($URL, $code = 303){ //TODO: Choose best redirect status code
        
        $this->redirect = [
            'URL' => $URL,
            'code' => $code
        ];
        $this->bufferFlush();
    }
    
    private function bufferStart(){
       //'\System\Core\View::bufferCallback'
        if(Config::obj()->get('output_buffering')){
            ob_start(array(self::obj(),'bufferCallback'));
        }
        
    }

    private function bufferCallback($buffer){
        
        if(!empty($this->redirect)){
            header('Location: ' . $this->redirect['URL'], true, $this->redirect['code']);
            exit();
        }else{            
            return $buffer;
        }
    }
    
    private  function bufferFlush(){
        if(Config::obj()->get('output_buffering')){
            ob_end_flush();
        }
    }
    
    public function load($route){
        
        $currentRoute = URI::obj()->route;
        $route = empty ($route) ? $currentRoute : $route;
        
        if(file_exists(Config::obj()->get('app_path').'/Views/'.$route.'.php')){
            require Config::obj()->get('app_path').'/Views/'.$route.'.php';
        }
    }
}

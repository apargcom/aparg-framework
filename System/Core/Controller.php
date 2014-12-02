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

    protected $config = null;    
    protected $URI = null;
                
    public function __construct() {

        $this->config = Config::obj();
        $this->URI = URI::obj();          
    }

    protected function view($route = '', $data = [], $return = false) { //TODO: Maybe better set View class instance and call $this->view->load() from child controller
        
        return View::obj()->load($route, $data, $return);
    }

    protected function redirect($URL, $code = 302) { //TODO: Maybe better set URL class instance and call $this->URL->redirect() from child controller                    
        URI::obj()->redirect($URL, $code);
    }

    protected function module($name) {

        return App::obj()->loadModule($name);
    }

    protected function model($name) {

        return App::obj()->loadModel($name);
    }
}

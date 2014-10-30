<?php

/**
 * Aparg Framework
 * 
 * @author Aparg
 * @link http://www.aparg.com/
 * @copyright Aparg
 */

namespace System\Core;

use \App;

abstract class Controller extends App {

    protected $view = null;

    protected function __construct() {

        parent::__construct();

        $this->view = new View();
    }

    public static function load($route, $vars) {

        $splitRoute = explode('/', $route);

        $tmpController = '\\' . ucfirst($splitRoute[0] . 'Controller');
        $tmpAction = $splitRoute[1] . 'Action';

        if (file_exists(Config::obj()->get('app_path') . '/Controllers/' . $tmpController . '.php')) {
            require_once Config::obj()->get('app_path') . '/Controllers/' . $tmpController . '.php';
            if (class_exists($tmpController, false)) {
                $controller = new $tmpController();
                if (method_exists($controller, $tmpAction)) {
                    $controller->$tmpAction($vars);
                    $controller->view->render();
                    return $controller;
                }
            }
        }
        $route_404 = Config::obj()->get('route_404');
        if ($route != $route_404) {
            $load_404 = self::load($route_404, $vars);
            if ($load_404 !== false) {
                http_response_code(404);
                return $load_404;
            }
        }
        return false;
    }

    protected function view($route = '', $data = [], $return = false) { //TODO: Maybe better set View class instance and call $this->view->load() from child controller
        return $this->view->load($route, $data, $return);
    }

    protected function redirect($URL, $code = 302) { //TODO: Maybe better set URL class instance and call $this->URL->redirect() from child controller                    
        $this->URI->redirect($URL, $code);
    }

    protected function module($name) {

        return Module::load($name);
    }

    protected function model($name) {

        return Model::load($name);
    }

}

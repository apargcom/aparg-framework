<?php

namespace System\Core;

use System\Core\Components\Config;
use System\Core\Components\Request;

/**
 * Aparg Framework {@link https://www.aparg.com}
 * 
 * Controller abstract class is parent class for app controller classes and contains methods for using in app controller classes
 * 
 * @author Aparg <info@aparg.com>
 * @copyright Aparg
 * @package System\Core
 * @abstract
 */
abstract class Controller {

    /**
     * Wrapper method for \System\Core\View::load()
     * 
     * @param string $route Route of view file to load
     * @param array $data Array with data to be passed to view file
     * @param boolean $return Whether to flush or return rendered view
     * @return string|boolean True or rendered data(if $return=true) on success, false on fail
     * @see \System\Core\View::load()
     */
    public function view($route = '', $data = [], $return = false) {

        return View::obj()->load($route, $data, $return);
    }

    /**
     * Wrapper method for \System\Core\Request::redirect()
     * 
     * @param string $URL URL to redirect
     * @param integer $code Status code to send with headers
     * @return void
     * @see \System\Core\Request::redirect()
     */
    protected function redirect($Url, $code = 302) {
        Request::obj()->redirect($Url, $code);
    }

    /**
     * Wrapper method for \System\Core\App::loadModule()
     * 
     * @param string $name Name of module to load(case-insensitive)
     * @return boolean|object Module object on success, false on fail
     * @see \System\Core\App::loadModule()
     */
    protected function module($name) {

        return App::obj()->loadModule($name);
    }

    /**
     * Wrapper method for \System\Core\App::loadModel()
     * 
     * @param string $name Name of model to load(case-insensitive)
     * @return boolean|object Model object on success, false on fail
     * @see \System\Core\App::loadModel()
     */
    protected function model($name) {

        return App::obj()->loadModel($name);
    }

    /**
     * Wrapper method for \System\Core\App::loadComponent()
     * 
     * @param string $name Name of core object to load(case-insensitive)
     * @return boolean|object Core object on success, false on fail
     * @see \System\Core\App::loadComponent()
     */
    public function component($name) {

        return App::obj()->loadComponent($name);
    }

    /**
     * Wrapper method for \System\Core\App::log()
     * 
     * @param string $type Type of log
     * @param string $message Log message
     * @return boolean True on success, false on fail
     * @see \System\Core\App::log()
     */
    protected function log($type, $message) {

        return App::obj()->log($type, $message);
    }

}

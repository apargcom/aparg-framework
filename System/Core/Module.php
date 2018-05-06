<?php

namespace System\Core;

/**
 * Aparg Framework {@link https://www.aparg.com}
 * 
 * Module abstract class is parent class for app module classes and contains methods for using in module classes
 * 
 * @author Aparg <info@aparg.com>
 * @copyright Aparg
 * @package System\Core
 * @abstract
 */
abstract class Module {

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

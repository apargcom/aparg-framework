<?php

namespace System\Core;

/**
 * Aparg Framework {@link https://www.aparg.com}
 * 
 * Model abstract class is parent class for app model classes and contains methods for using in model classes
 * 
 * @author Aparg <info@aparg.com>
 * @copyright Aparg
 * @package System\Core
 * @abstract
 */
abstract class Model {

    /**
     * Wrapper method for \System\Core\App::loadComponent()
     * 
     * @param string $name Name of core object to load(case-insensitive)
     * @return boolean|object Core object on success, false on fail
     * @see \System\Core\App::loadComponente()
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

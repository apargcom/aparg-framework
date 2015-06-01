<?php

namespace System\Core;

/**
 * Aparg Framework {@link http://www.aparg.com}
 * 
 * Model abstract class is parent class for app model classes and contains methods for using in model classes
 * 
 * @author Aparg <info@aparg.com>
 * @copyright Aparg
 * @package System
 * @subpackage Core
 * @abstract
 */
abstract class Model {

    /**
     * Initialize Model class
     * @return void
     */
    public function __construct() {
        
    }

    /**
     * Wrapper method for \System\Core\App::loadCore()
     * 
     * @param string $name Name of core object to load(case-insensitive)
     * @return boolean|object Core object on success, false on fail
     * @see \System\Core\App::loadCore()
     */
    public function core($name) {

        return App::obj()->loadCore($name);        
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

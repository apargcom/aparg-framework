<?php

namespace System\Core;

/**
 * Aparg Framework {@link http://www.aparg.com}
 * 
 * Model abstract class is parent class for app model classes and contains methods for using in model classes
 *
 * @version 1.0
 * @author Aparg <info@aparg.com>
 * @copyright Aparg
 * @package System
 * @subpackage Core
 * @abstract
 */
abstract class Model {

    /**
     * @var DB Instance of DB class
     * @see DB
     */
    protected $DB = null;

    /**
     * Initialize Model class
     * @return void
     */
    public function __construct() {

        if (!DB::isObj()) {
            DB::obj()->init();
        }
        $this->DB = DB::obj();
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
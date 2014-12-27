<?php

namespace System\Core;

/**
 * Aparg Framework {@link http://www.aparg.com}
 * 
 * Module abstract class is parent class for app module classes and contains methods for using in module classes
 * 
 * @author Aparg <info@aparg.com>
 * @copyright Aparg
 * @package System
 * @subpackage Core
 * @abstract
 */
abstract class Module {

    /**
     * @var DB Instance of DB class
     * @see DB
     */
    protected $DB = null;
    /**
     * @var Config Instance of Config class
     * @see Config
     */
    protected $config = null;
    /**
     * @var URI Instance of URI class
     * #see URI
     */
    protected $URI = null;


    /**
     * Initialize Model class
     * @return void
     */
    public function __construct() {


        $this->config = Config::obj();
        $this->URI = URI::obj();

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
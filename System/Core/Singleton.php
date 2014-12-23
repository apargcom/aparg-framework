<?php

namespace System\Core;

/**
 * Aparg Framework {@link http://www.aparg.com}
 * 
 * Singleton abstract class parent class for all singleton classes
 *
 * @version 1.0
 * @author Aparg <info@aparg.com>
 * @copyright Aparg
 * @package System
 * @subpackage Core
 * @abstract
 */
abstract class Singleton {

    /**
     * @var array Array that contains all child singleton classes instances
     */
    private static $instances;

    /**
     * Return reference to child class instance
     * 
     * @return object Reference to child class object
     */
    final public static function &obj() {
        $className = get_called_class();

        if(!isset(self::$instances[$className])) {          
            self::$instances[$className] = new static();
        }
        return self::$instances[$className];
    }
    
    /**
     * Check whether child class instance is created
     * @return boolean True if created, false if not
     */
    final public static function isObj(){
        
        $className = get_called_class();        
        return isset(self::$instances[$className]);
    }

    /**
     * Disallow to create child class instance from outside
     */
    final protected function  __construct() { } 
    
    /**
     * Disallow to clone child class instance
     */
    final public function __clone() { }

    /**
     * Disallow to wakeup child class instance
     */
    final public function __wakeup() { }

}
<?php

/**
 * Aparg Framework
 * 
 * @author Aparg
 * @link http://www.aparg.com/
 * @copyright Aparg
 */
namespace System\Core;

abstract class Singleton {

    private static $instances;

    final public static function &obj() {
        $className = get_called_class();

        if(!isset(self::$instances[$className])) {          
            self::$instances[$className] = new static();
        }
        return self::$instances[$className];
    }
    

    final protected function  __construct() { } //TODO: do we need "final" keyword here
    
    final public function __clone() { } //TODO: do we need "final" keyword here

    final public function __wakeup() { } //TODO: do we need "final" keyword here

}



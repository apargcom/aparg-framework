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

abstract class Module extends App {

    protected $DB = null;
    
    protected function __construct() {

        parent::__construct();

        //Init DB
        DB::init(Config::obj()->get('db_host'), Config::obj()->get('db_username'), Config::obj()->get('db_password'), Config::obj()->get('db_name'));
        $this->DB = DB::obj();
    }

    public static function load($name, $system = true) {

        $name = ucfirst($name);
        // $path = ($system ? Config::obj()->get('system_path') : Config::obj()->get('app_path')) . '/Modules/' . $name . '.php';
        $class = '\\' . ($system ? 'System' : 'App') . '\Modules\\' . $name;

        if (class_exists($class)) {
            $classObj = new $class();
            return $classObj;
        }
        if ($system)
            return self::load($name, false);
        return false;
    }

}

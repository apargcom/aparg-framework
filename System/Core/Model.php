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

class Model extends App {

    protected $DB = null;
            
    protected function __construct(){
        
        parent::__construct();
        
        //Init DB
        DB::init(Config::obj()->get('db_host'), Config::obj()->get('db_username'), Config::obj()->get('db_password'), Config::obj()->get('db_name'));
        $this->DB = DB::obj();
    }
    
    public static function load($name){ 
    
        $name = ucfirst($name);
       // $path = ($system ? Config::obj()->get('system_path') : Config::obj()->get('app_path')) . '/Modules/' . $name . '.php';
        $class = '\App\Models\\' . $name;        
       
        if(class_exists($class)){ 
            $classObj = new $class();                                
            return $classObj;            
        }         
        return false;       
    }
}

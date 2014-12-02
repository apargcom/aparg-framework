<?php

/**
 * Aparg Framework
 * 
 * @author Aparg
 * @link http://www.aparg.com/
 * @copyright Aparg
 */

namespace System\Core;



abstract class Model {

    protected $DB = null;
            
    public function __construct(){
                
        if(!DB::isObj()){
            DB::obj()->init(Config::obj()->get('db_host'), Config::obj()->get('db_username'), Config::obj()->get('db_password'), Config::obj()->get('db_name'));
        }
        $this->DB = DB::obj();
    }
}

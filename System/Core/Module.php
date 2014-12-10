<?php

/**
 * Aparg Framework
 * 
 * @author Aparg
 * @link http://www.aparg.com/
 * @copyright Aparg
 */

namespace System\Core;

abstract class Module {

    protected $DB = null;
    protected $config = null;
    protected $URI = null;

    public function __construct() {


        $this->config = Config::obj();
        $this->URI = URI::obj();

        if (!DB::isObj()) {
            DB::obj()->init(Config::obj()->get('db_host'), Config::obj()->get('db_username'), Config::obj()->get('db_password'), Config::obj()->get('db_name'));
        }
        $this->DB = DB::obj();
    }

    protected function log($type, $message) {

        return App::obj()->log($type, $message);
    }

}

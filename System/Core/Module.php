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
            DB::obj()->init();
        }
        $this->DB = DB::obj();
    }

    protected function log($type, $message) {

        return App::obj()->log($type, $message);
    }

}

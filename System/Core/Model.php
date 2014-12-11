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

    public function __construct() {

        if (!DB::isObj()) {
            DB::obj()->init();
        }
        $this->DB = DB::obj();
    }

    protected function log($type, $message) {

        return App::obj()->log($type, $message);
    }

}

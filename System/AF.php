<?php

/**
 * Aparg Framework
 * 
 * @author Aparg
 * @link http://www.aparg.com/
 * @copyright Aparg
 */

use \System\Core\App;

class AF {

    public static function start($config = []) {

        require_once __DIR__ . DIRECTORY_SEPARATOR . 'Core' . DIRECTORY_SEPARATOR . 'App.php';
        App::obj()->init($config);
    }
}

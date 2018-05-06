<?php

use \System\Core\App;

/**
 * Aparg Framework {@link https://www.aparg.com}
 * 
 * AF class is for starting framework
 *
 * @author Aparg <info@aparg.com>
 * @copyright Aparg
 */
class AF {

    /**
     * Starts framework
     * 
     * @param array $config User defined configs
     * @static
     * @return void
     */
    public static function start($config = []) {

        require_once __DIR__ . DIRECTORY_SEPARATOR . 'Core' . DIRECTORY_SEPARATOR . 'App.php';
        App::obj()->init($config);
    }

}

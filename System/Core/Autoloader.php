<?php

namespace System\Core;

use System\Core\Components\Config;

/**
 * Aparg Framework {@link http://www.aparg.com}
 * 
 * Autoloader class is for automatically loading class
 * 
 * @author Aparg <info@aparg.com>
 * @copyright Aparg
 * @package System\Core
 */
class Autoloader extends Singleton {

    /**
     * @var array Aliases of classes
     */
    private $aliases = [];

    /**
     * @var string Path to system folder
     */
    private $systemPath = '';

    /**
     * @var string Path to app folder
     */
    private $appPath = '';

    /**
     * Initialize Autoloader class
     * 
     * @return void
     */
    public function __construct() {

        $this->aliases = Config::obj()->get('aliases');
        $this->systemPath = Config::obj()->get('system_path');
        $this->appPath = Config::obj()->get('app_path');
        spl_autoload_register([$this, 'load']);
    }

    /**
     * Callback method of loading class
     * 
     * @param string $class Name of the class
     * @return void
     */
    public function load($class) {

        if (isset($this->aliases[$class])) {
            class_alias('\\' . trim($this->aliases[$class], '\\'), $class);
            return true;
        }
        $this->loadClass($class);
    }

    /**
     * Loads class
     * 
     * @param string $class Name of the class
     * @return void
     */
    private function loadClass($class) {

        $packages = explode('\\', $class);

        $mainPackage = $packages[0];
        unset($packages[0]);

        $path = implode(DIRECTORY_SEPARATOR, $packages);
        if ($mainPackage == 'System') {
            $fileName = $this->systemPath . DIRECTORY_SEPARATOR . $path . ".php";
        } else if ($mainPackage == 'App') {
            $fileName = $this->appPath . DIRECTORY_SEPARATOR . $path . ".php";
        }

        if (file_exists($fileName)) {
            require_once $fileName;
        }
    }

}

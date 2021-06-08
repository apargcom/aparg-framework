<?php

namespace System\Modules;

/**
 * Aparg Framework {@link https://www.aparg.com}
 * 
 * Dotenv class is system module for loading .env files
 * 
 * @author Aparg <info@aparg.com>
 * @copyright Aparg
 * @package System\Modules
 */
class Dotenv extends \Module {
    
    /**
     * @var string Path to .env file
     */
    private $path = '';

    /**
     * Loads some configs
     * 
     * @return void
     */
    public function __construct() {

        $this->config = $this->component('config');
        $this->path = $this->config->get('env_path');        
    }

    /**
     * Loads .env file and add variables to env
     * 
     * @return boolean True on success, false on fail
     */
    public function load($path='') {
       
        $path = ($path == '') ? $this->path : $path;

        if(!file_exists($path) || !is_readable($path)) {            
            return false;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {

            if (strpos(trim($line), '#') === 0) {
                continue;
            }

            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value, '"');
            $lwrValue = strtolower($value);
            $value = in_array($lwrValue, ['true' , 'false']) ? ($lwrValue == 'true' ? true : false) : $value;

            if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
                putenv(sprintf('%s=%s', $name, $value));
                $_ENV[$name] = $value;
                $_SERVER[$name] = $value;
            }
        }
        return true;
    }
}
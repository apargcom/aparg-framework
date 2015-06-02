<?php

namespace System\Core\Components;

use System\Core\Singleton;

/**
 * Aparg Framework {@link http://www.aparg.com}
 * 
 * Config class is for setting/getting configurations
 * 
 * @author Aparg <info@aparg.com>
 * @copyright Aparg
 * @package System\Core\Components
 */
class Config extends Singleton {

    /**
     * @var array Array with configs
     */
    private $config;

    /**
     * Initialize Config class
     * 
     * @return void
     */
    public function __construct() {

        $this->config = $this->defaults();
    }

    /**
     * Get default system configs
     * 
     * @return array Array with configs
     */
    private function defaults() {

        return require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'config.php';
    }

    /**
     * Get config for given key
     * 
     * @param string $key Key of config to get
     * @return boolean|mixed Config date on success, false on fail
     */
    public function get($key = '') {

        if (isset($this->config[$key])) {
            return $this->config[$key];
        } else {
            return false;
        }
    }

    /**
     * Set new config
     * 
     * @param array $config Array with config Ex.:['config_key'=>'config_value']
     * @return boolean True on success, false on fail
     */
    public function set($config = []) {

        $this->config = array_replace_recursive($this->config, $config);
        if ($this->config != null) {
            return true;
        } else {
            return false;
        }
    }

}

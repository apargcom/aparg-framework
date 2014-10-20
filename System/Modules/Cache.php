<?php

/**
 * Aparg Framework
 * 
 * @author Aparg
 * @link http://www.aparg.com/
 * @copyright Aparg
 */

namespace System\Modules;

use \System\Core\Config;

class Cache {

    private $expire = 3600;
    private $path = '';

    public function __construct() {

        $this->path = Config::obj()->get('cache_path');
        $this->expire = Config::obj()->get('cache_expire');
        if (!file_exists($this->path) && !empty($this->path)) {
            mkdir($this->path, 0777, true);
        }
    }

    public function get($key) {
        $files = glob($this->path . '/cache.' . preg_replace('/[^A-Z0-9\._-]/i', '', $key) . '.*');

        if ($files) {
            $cache = file_get_contents($files[0]);

            $data = unserialize($cache);

            array_map(function($file) {

                $time = substr(strrchr($file, '.'), 1);
                if ($time < time()) {
                    if (file_exists($file)) {
                        unlink($file);
                    }
                }
            }, $files);

            return $data;
        }else{
            return false;
        }
    }

    public function set($key, $value) {
        $this->delete($key);

        $file = $this->path . '/cache.' . preg_replace('/[^A-Z0-9\._-]/i', '', $key) . '.' . (time() + $this->expire);

        return file_put_contents($file, serialize($value));
    }

    public function delete($key) {
        $files = glob($this->path . '/cache.' . preg_replace('/[^A-Z0-9\._-]/i', '', $key) . '.*');

        if ($files) {
            foreach ($files as $file) {
                if (file_exists($file)) {
                    unlink($file);
                }
            }
            return true;
        }else{
            return false;
        }
    }

}

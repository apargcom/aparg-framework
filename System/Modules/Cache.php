<?php

/**
 * Aparg Framework
 * 
 * @author Aparg
 * @link http://www.aparg.com/
 * @copyright Aparg
 */

namespace System\Modules;

use \System\Core\Module;

class Cache extends Module{

    private $expire = 3600;
    private $path = '';

    public function __construct() {
        parent::__construct();

        $this->path = $this->config->get('cache_path') == true ? $this->config->get('cache_path') : $this->path;
        $this->expire = $this->config->get('cache_expire') == true ? $this->config->get('cache_expire') : $this->cache_expire;
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

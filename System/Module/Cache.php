<?php

/**
 * Aparg Framework
 * 
 * @author Aparg
 * @link http://www.aparg.com/
 * @copyright Aparg
 */

namespace System\Module;

use \System\Core\Singleton;

class Cache extends Singleton{

    private $expire = 3600;
    
    private $path = '';

    public static function init($path, $expire){
        
        self::obj()->path = $path;
        self::obj()->expire = $expire;
        if (!file_exists(self::obj()->path) && !empty(self::obj()->path)) {
            mkdir(self::obj()->path, 0777, true);
        }
    }
    
    public function get($key) {
        $files = glob($this->path . '/cache.' . preg_replace('/[^A-Z0-9\._-]/i', '', $key) . '.*');

        if ($files) {
            $cache = file_get_contents($files[0]);

            $data = unserialize($cache);

            foreach ($files as $file) {
                $time = substr(strrchr($file, '.'), 1);

                if ($time < time()) {
                    if (file_exists($file)) {
                        unlink($file);
                    }
                }
            }

            return $data;
        }
    }

    public function set($key, $value) {
        $this->delete($key);

        $file = $this->path . '/cache.' . preg_replace('/[^A-Z0-9\._-]/i', '', $key) . '.' . (time() + $this->expire);

        $handle = fopen($file, 'w');

        fwrite($handle, serialize($value));

        fclose($handle);
    }

    public function delete($key) {
        $files = glob($this->path . '/cache.' . preg_replace('/[^A-Z0-9\._-]/i', '', $key) . '.*');

        if ($files) {
            foreach ($files as $file) {
                if (file_exists($file)) {
                    unlink($file);
                }
            }
        }
    }

}

<?php

namespace System\Modules;

/**
 * Aparg Framework {@link http://www.aparg.com}
 * 
 * Cache class is system module for fast storing/getting data from cache file
 * 
 * @author Aparg <info@aparg.com>
 * @copyright Aparg
 * @package System
 * @subpackage Modules
 */
class Cache extends \Module{

    /**
     * @var integer Cache file life time
     */
    private $expire = 3600;
    /**
     * @var string Path to cache file folder
     */
    private $path = '';

    /**
     * Loads some configs and create cache folder if not exist
     * 
     * @return void
     */
    public function __construct() {
        parent::__construct();

        $this->path = $this->config->get('cache_path') == true ? $this->config->get('cache_path') : $this->path;
        $this->expire = $this->config->get('cache_expire') == true ? $this->config->get('cache_expire') : $this->cache_expire;
        if (!file_exists($this->path) && ($this->path != '')) {
            mkdir($this->path, 0777, true);
        }
    }

    /**
     * Get data from cache for specific key
     * 
     * @param string $key Key of data to get
     * @return boolean|array False on fail, array with data on success
     */
    public function get($key) {
        $files = glob($this->path . '/cache.' . preg_replace('/[^A-Z0-9\._-]/i', '', $key) . '.*');

        if ($files) {
            
            foreach($files as $key=>$file){
                 $time = substr(strrchr($file, '.'), 1);
                if ($time < time()) {
                    if (file_exists($file)) {
                        unlink($file);
                        unset($files[$key]);
                    }
                }                
            }
            
            if(isset($files[0])){
                $cache = file_get_contents($files[0]);
                $data = unserialize($cache);
                return $data;
            }            
        }
        
        return false;
    }

    /**
     * Store data to cache for specific key
     * 
     * @param string $key Key of data to set
     * @param mixed $value Data to Store
     * @return boolean True on success, false on fail
     */
    public function set($key, $value) {
        $this->delete($key);

        $file = $this->path . '/cache.' . preg_replace('/[^A-Z0-9\._-]/i', '', $key) . '.' . (time() + $this->expire);

        return file_put_contents($file, serialize($value));
    }

    /**
     * Delete caches data for specific key
     * 
     * @param string $key Key of data to delete
     * @return boolean True on success, false on fail
     */
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
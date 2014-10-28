<?php

/**
 * Aparg Framework
 * 
 * @author Aparg
 * @link http://www.aparg.com/
 * @copyright Aparg
 */

namespace System\Modules;

class Lang extends \Module{
    //TODO: Database storage must be added too
    private $lang = '';
    
    public function __construct() {
        parent::__construct();
        
        $this->lang = $this->URI->lang;
    }
    
    public function get($key, $lang = ''){
        if(empty($lang)){
            $lang = $this->lang;
        }
        $path = $this->config->get('lang_path') . '/' . $lang . '.php';
        if(file_exists($path)){
            $langData = require_once $path;
            if(isset($langData[$key])){
                return $langData[$key];
            }
        }
        return false;
    }    
}

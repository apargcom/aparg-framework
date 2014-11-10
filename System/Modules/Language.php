<?php

/**
 * Aparg Framework
 * 
 * @author Aparg
 * @link http://www.aparg.com/
 * @copyright Aparg
 */

namespace System\Modules;

class Language extends \Module{
    
    private $lang = '';
    
    private $langData = [];
    
    public function __construct() {
        parent::__construct();
        
        $this->lang = $this->URI->lang;
        $this->langData = $this->langData($this->lang);
    }
    
    public function get($key, $lang = ''){
        
        $langData = empty($lang) ? $this->langData : $this->langData($lang);
        if(isset($langData[$key])){
            return $langData[$key];
        }        
        return false;
    } 
    
    public function &langData($lang){
        
        $path = $this->config->get('lang_path') . DIRECTORY_SEPARATOR . $lang . '.php';
        if(file_exists($path)){
            $langData = require_once $path;
            return $langData;           
        }
        return false;
    }    
}

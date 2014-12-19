<?php

namespace System\Modules;

/**
 * Aparg Framework {@link http://www.aparg.com}
 * 
 * Language class is system module for adding multilanguage functionality
 *
 * @version 1.0
 * @author Aparg <info@aparg.com>
 * @copyright Aparg
 * @package System
 * @subpackage Modules
 */
class Language extends \Module{
    
    /**
     * @var string Current language
     */
    private $lang = ''; 
    /**
     * @var array Array with translations for language. Key is translation label, value is content
     */
    private $langData = [];
    /**
     * @var string Path to language files folder
     */
    private $path = '';
    
    /**
     * Loads some configs and language data for current language
     * 
     * @return void
     */
    public function __construct() {
        parent::__construct();
        
        $this->path = $this->config->get('lang_path');
        $this->lang = $this->URI->lang;        
        $this->langData = $this->langData($this->lang);
    }
    
    /**
     * Get translation for key
     * 
     * @param string $key Key of translation
     * @param string $lang Language for translation(if empty current language is selected)
     * @return boolean
     */
    public function get($key, $lang = ''){
        
        $langData = empty($lang) ? $this->langData : $this->langData($lang);
        if(isset($langData[$key])){
            return $langData[$key];
        }        
        return false;
    } 
    
    /**
     * Get translation data
     * 
     * @param string $lang Language of expecting data
     * @return boolean|array False on fail, array with data on success
     */
    private function langData($lang){
        
        $lang = strtolower($lang);
        $path = $this->path . DIRECTORY_SEPARATOR . $lang . '.php';
        if(file_exists($path)){
            $langData = require_once $path;
            return $langData;           
        }
        return false;
    }    
}

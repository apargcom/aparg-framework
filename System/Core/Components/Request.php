<?php

namespace System\Core\Components;

use System\Core\Singleton;

/**
 * Aparg Framework {@link http://www.aparg.com}
 * 
 * Request class is working with current request
 * 
 * @author Aparg <info@aparg.com>
 * @copyright Aparg
 * @package System\Core\Components
 */
class Request extends Singleton {

    /**
     * @var string Current URI
     */
    public $Uri = '';

    /**
     * @var string Current route
     */
    public $route = '';

    /**
     * @var array Array with GET, POST and URI variables
     */
    public $vars = [];

    /**
     * @var string Current language
     */
    public $lang = '';

    /**
     * @var string Default language
     */
    private $defaultLanguage = '';

    /**
     * @var array Array with available language
     */
    private $languages = [];

    /**
     * @var string Default controller 
     */
    private $defaultController = '';

    /**
     * @var array Array with routes that will be replace in current URI
     */
    private $routes = [];

    /**
     * Initialize Request class
     * 
     * @return void
     */
    public function __construct() {

        $this->languages = Config::obj()->get('languages');
        $this->defaultLanguage = Config::obj()->get('default_language');
        $this->defaultController = Config::obj()->get('default_controller');
        $this->routes = Config::obj()->get('routes');
        $this->Uri = $_SERVER['REQUEST_URI'];

        $this->parse();
    }

    /**
     * Parse current URI. Grab route and variables
     * 
     * @return void
     */
    private function parse() {

        $filteredUri= trim(strtok(preg_replace('/[\/]+/', '/', $this->Uri), '?'), '/');
        $filteredUri = $this->route($filteredUri);
        $splittedUri = array_filter(explode('/', $filteredUri));

        $this->lang = $this->defaultLanguage;
        if (isset($splittedUri[0]) && array_search(strtolower($splittedUri[0]), array_map('strtolower', $this->languages)) !== false) {
            $this->lang = strtolower($splittedUri[0]);
            unset($splittedUri[0]);
            $splittedUri = array_values($splittedUri);
        }

        $route[0] = isset($splittedUri[0]) ? $splittedUri[0] : $this->defaultController;
        $route[1] = isset($splittedUri[1]) ? $splittedUri[1] : 'index';
        unset($splittedUri[0]);
        unset($splittedUri[1]);
        $splittedUri = array_values($splittedUri);

        $this->route = $route[0] . '/' . $route[1];
        $this->vars = [
            'URI' => array_values($splittedUri),
            'GET' => $_GET,
            'POST' => $_POST
        ];
    }

    /**
     * Replace all matched routes in given URI with $routes array
     * 
     * @param string $URI URI to apply routing
     * @see $routes
     */
    private function route($Uri) {

        foreach ($this->routes as $from => $to) {
            $Uri = preg_replace('/^' . preg_quote($from, '/') . '/i', $to, $Uri);
        }
        return $Uri;
    }

    /**
     * Set Location header to redirect by given URL
     * 
     * @param string $URL URL to redirect
     * @param integer $code Status code to send with headers
     * @return void
     */
    public function redirect($Url, $code = 302) {

        header('Location: ' . $Url, true, $code);
    }
    
    /**
     * Check if request is AJAX request
     * 
     * @return boolean True on success, false on fail
     */
    public function isAjax() {
        
        return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') ? true : false;
    }

}

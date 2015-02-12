<?php

namespace System\Core;

/**
 * Aparg Framework {@link http://www.aparg.com}
 * 
 * URI class is working with URIs
 * 
 * @author Aparg <info@aparg.com>
 * @copyright Aparg
 * @package System
 * @subpackage Core
 */
class URI extends Singleton {

    /**
     * @var string Current URI
     */
    public $URI = '';

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
     * Initialize URI
     * 
     * @param string $URI URI to work with
     * @return void
     */
    public function init($URI = '') {

        $this->languages = Config::obj()->get('languages');
        $this->defaultLanguage = Config::obj()->get('default_language');
        $this->defaultController = Config::obj()->get('default_controller');
        $this->routes = Config::obj()->get('routes');
        $this->URI = $URI;

        $this->parse();
    }

    /**
     * Parse current URI. Grab route and variables
     * 
     * @return void
     */
    private function parse() {

        $filteredURI = trim(strtok(preg_replace('/[\/]+/', '/', $this->URI), '?'), '/');
        $filteredURI = $this->route($filteredURI);
        $splittedURI = explode('/', $filteredURI);

        $this->lang = $this->defaultLanguage;
        if (isset($splittedURI[0]) && array_search($splittedURI[0], $this->languages) !== false) {
            $this->lang = $splittedURI[0];
            unset($splittedURI[0]);
            $splittedURI = array_values($splittedURI);
        }

        $route[0] = isset($splittedURI[0]) ? $splittedURI[0] : $this->defaultController;
        $route[1] = isset($splittedURI[1]) ? $splittedURI[1] : 'index';
        unset($splittedURI[0]);
        unset($splittedURI[1]);
        $splittedURI = array_values($splittedURI);

        $this->route = $route[0] . '/' . $route[1];
        $this->vars = [
            'URI' => array_values($splittedURI),
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
    private function route($URI) {

        foreach ($this->routes as $from => $to) {
            $URI = preg_replace('/^' . preg_quote($from, '/') . '/i', $to, $URI);
        }
        return $URI;
    }

    /**
     * Set Location header to redirect by given URL
     * 
     * @param string $URL URL to redirect
     * @param integer $code Status code to send with headers
     * @return void
     */
    public function redirect($URL, $code = 302) {

        header('Location: ' . $URL, true, $code);
    }

}

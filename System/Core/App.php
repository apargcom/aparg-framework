<?php

namespace System\Core;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'Singleton.php';

/**
 * Aparg Framework {@link http://www.aparg.com}
 * 
 * App class is main framework class that initialize the app. Also contains important methods for framework functionality
 *
 * @author Aparg <info@aparg.com>
 * @copyright Aparg
 * @package System
 * @subpackage Core
 */
class App extends Singleton{
    
    /**
     * @var object Loaded controller 
     */
    public $conroller = null;
    /**
     * @var string Path to logs folder
     */    
    private $logsPath = null;
    /**
     * @var boolean Enable/disable logs
     */
    private $enableLogs = true;
    /**
     * @var string Path to application folder
     */
    private $appPath = null;
    /**
     * @var array Array which 0 element is route of not found page,
     *            1 element is for enable/disable sending 404 status code with headers
     */
    private $notFound = null;
    
    /**
     * Initialize the application
     * 
     * @param array $config User defined configs
     * @return void
     */
    public function init($config = []){        
        
        require_once __DIR__ . DIRECTORY_SEPARATOR . 'Config.php';
        Config::obj()->init($config);          
        
        require_once __DIR__ .  DIRECTORY_SEPARATOR . 'Autoloader.php';
        Autoloader::obj()->init();
        
        $this->logsPath = Config::obj()->get('logs_path');
        $this->enableLogs = Config::obj()->get('enable_logs');
        $this->appPath = Config::obj()->get('app_path');
        $this->notFound = Config::obj()->get('not_found');
        $this->notFound = is_array($this->notFound) ? $this->notFound : ['route' => $this->notFound];
        
        if(phpversion() < Config::obj()->get('min_php_version')){ 
            trigger_error('Suported PHP version is 5.3.3 and above.', E_USER_ERROR);                    
        }
 
        error_reporting((Config::obj()->get('show_errors')) ? -1 : 0);
        
        URI::obj()->init($_SERVER['REQUEST_URI']);                                
                
        View::obj()->init();
        $this->controller = $this->loadController(URI::obj()->route, URI::obj()->vars);
        if($this->controller != false){
            View::obj()->render();
        }
    }
    
    /**
     * Add log to log file
     * 
     * @param string $type Type of log
     * @param string $message Log message
     * @return boolean True on success, false on fail
     */
    public function log($type, $message){
                
        if($this->logsPath){            
            $log = '(' . date("Y-m-d H:i:s") . ') ' . $type . ': ' .  $message;                    
            return (file_put_contents($this->logsPath, $log . PHP_EOL, FILE_APPEND) == false) ? false : true;
        }else{
            return false;
        }
    }
    
    /**
     * Load controller
     * 
     * @param string $route Route to controller(case-insensitive)
     * @param mixed $vars Variables that are being passed to action
     * @return boolean|object Controller object on success, false on fail
     */
    private function loadController($route, $vars) {

        $splitRoute = explode('/', $route);

        $tmpController = ucfirst(strtolower($splitRoute[0])) . 'Controller';
        $tmpAction = strtolower($splitRoute[1]) . 'Action';

        if (file_exists($this->appPath . DIRECTORY_SEPARATOR . 'Controllers' . DIRECTORY_SEPARATOR . $tmpController . '.php')) {
            require_once $this->appPath . DIRECTORY_SEPARATOR . 'Controllers' . DIRECTORY_SEPARATOR . $tmpController . '.php';
            $tmpController = '\\' . $tmpController;
            if (class_exists($tmpController, false)) {
                $controller = new $tmpController();
                if (method_exists($controller, $tmpAction)) {
                    $controller->$tmpAction($vars);                    
                    return $controller;
                }
            }
        }
        
        if ($route != $this->notFound['route']) {
            $controller = $this->loadController($this->notFound['route'], $vars);
            if ($controller !== false) {                   
                if(!isset($this->notFound['404']) || $this->notFound['404'] ==  true){
                    header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 404);  
                }
                return $controller;
            }
        }
        return false;
    }
    
    /**
     * Load model
     * 
     * @param string $name Name of model to load(case-insensitive)
     * @return boolean|object Model object on success, false on fail
     */
    public function loadModel($name){ 
        
        $name = implode('\\', array_map(function($value) {
                return ucfirst(strtolower($value));
            }, explode('\\', $name)));
        $class = '\App\Models\\' . $name;        
       
        if(class_exists($class)){ 
            $classObj = new $class();                                
            return $classObj;            
        }         
        return false;       
    }
    
    /**
     * Load module
     * 
     * @param string $name Name of module to load(case-insensitive)
     * @param type $system
     * @return boolean|object Module object on success, false on fail
     */
    public function loadModule($name, $system = true) {
        
        $name = implode('\\', array_map(function($value) {
                return ucfirst(strtolower($value));
            }, explode('\\', $name)));
        $class = '\\' . ($system ? 'System' : 'App') . '\Modules\\' . $name;

        if (class_exists($class)) {
            $classObj = new $class();
            return $classObj;
        }
        if ($system)
            return $this->loadModule($name, false);
        return false;
    }
}
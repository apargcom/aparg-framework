<?php

/**
 * array['af_version']          string      Framework version
 *      ['db_name']             string      Database name
 *      ['db_username']         string      Database username
 *      ['db_password']         string      Database password
 *      ['db_host']             string      Database host
 *      ['db_charset']          string      Database character set
 *      ['db_engine']           string      Database engine
 *      ['html_charset']        string      HTML character set
 *      ['base_url']            string      Base URL for site
 *      ['base_path']           string      Server base path
 *      ['app_path']            string      Application folder path
 *      ['system_path']         string      System folder path
 *      ['cache_path']          string      Cache folder path(Cache system module)
 *      ['lang_path']           string      Language folder path
 *      ['logs_path']           string      Logs folder path(Logs system module)
 *      ['default_controller']  string      Framework default controller is loaded when controller missing in URI
 *      ['not_found']           array       Configurations for page not found case
 *          ['route']           string      Controller/action pair. Goes here if route in URI not found
 *          ['404']             boolean     Whether to enable/disable sending 404 status code with headers
 *      ['routes']              array       Array with routes that will be replaced in URI before parsing it. 
 *                                          Key is "from" URI value is to URI
 *      ['min_php']             string      Minimal PHP version that reuires framework
 *      ['cache_expire']        integer     Cache files exparation time in milliseconds(Cache system module)
 *      ['show_errors']         boolean     Enable/disable throwing PHP errors
 *      ['enable_logs']         boolean     Enable/disable writing logs(Logs system module)
 *      ['output_buffering']    boolean     Enable/disable output buffering when rendering view files
 *      ['default_language']    string      Default language of framework when it's not set in URI(Language system module)
 *      ['languages']           array       Array with languages to be used in framework(Language system module)
 *      ['mail_from']           string      Default sender email address(Mail system module)
 *      ['aliases']             array       Array with class aliases used when auto loading classes
 * 
 * @var array Contains configurations for framework. Any value can be override in application config file
 */
return [

    'af_version' => '0.0.20',
    'db_name' => '',
    'db_username' => '',
    'db_password' => '',
    'db_host' => 'localhost',
    'db_charset' => 'utf8',
    'db_engine' => 'innoDB',
    'html_charset' => 'utf-8',
    'base_url' => 'https://' . $_SERVER['SERVER_NAME'],
    'base_path' => ($base_path = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . '..'),
    'app_path' => ($app_path = $base_path . DIRECTORY_SEPARATOR . "app"),
    'system_path' => $base_path . DIRECTORY_SEPARATOR . "system",
    'cache_path' => $app_path . DIRECTORY_SEPARATOR . 'Cache',
    'lang_path' => $app_path . DIRECTORY_SEPARATOR . 'Lang',
    'logs_path' => $app_path . DIRECTORY_SEPARATOR . 'Logs' . DIRECTORY_SEPARATOR . 'logs.txt',
    'default_controller' => ($default_controller = "index"),
    'not_found' => [
        'route' => $default_controller . '/default404',
        '404' => true
    ],
    'routes' => [
    ],
    'min_php' => '5.3.0',
    'cache_expire' => 3600,
    'show_errors' => true,
    'enable_logs' => true,
    'output_buffering' => true,
    'default_language' => 'en',
    'languages' => [
        'en'
    ],
    'mail_from' => '',
    'aliases' => [
        'Controller' => 'System\Core\Controller',
        'Model' => 'System\Core\Model',
        'Module' => 'System\Core\Module',
    ]
];

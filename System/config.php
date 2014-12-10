<?php

return [
    
    'db_charset' => 'utf8',
    'html_charset' => 'utf-8',
    'db_engine' => 'innoDB',

    'base_url' => 'http://' . $_SERVER['SERVER_NAME'],
    'base_path' => ($base_path = $_SERVER['DOCUMENT_ROOT']),

    'app_path' => ($app_path = $base_path . "/App"),
    'system_path' => $base_path . "/System",
    'cache_path' => $app_path . '/Cache',
    'lang_path' => $app_path . '/Lang',
    'logs_path' => $app_path . '/Logs/logs.txt',
    'enable_logs' => true,
    
    'default_controller' => ($default_controller = "index"),
    
    'route_404' => $default_controller.'/default404',
    
    'routes' => [
    ],
    
    'min_php_version' => '5.3.0',
    
    'cache_expire' => '3600',

    'show_errors' => true,

    'output_buffering' => true,
    
    'default_lang' => 'en',
    
    'lang' => [
        'en',
        'am'
    ],
    
    'aliases'=> [
        'Controller' => 'System\Core\Controller',
        'Model' => 'System\Core\Model',
        'Module' => 'System\Core\Module',
//        'Config' => 'System\Core\Config',
    ]
    
];

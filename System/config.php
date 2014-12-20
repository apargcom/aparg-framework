<?php

return [
    
    'db_name' => '',    
    'db_username' => '',
    'db_password' => '',
    
    'db_host' => 'localhost',
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
    
    'not_found' => [
        'route'=>$default_controller.'/default404',
        '404'=>true
    ],
    
    'routes' => [
    ],
    
    'min_php' => '5.3.0',
    
    'cache_expire' => '3600',

    'show_errors' => true,

    'output_buffering' => true,
    
    'default_language' => 'en',
    
    'languages' => [
        'en',
        'am'
    ],
    
    'mail_from' => 'popok@popok.com',
    
    
    'aliases'=> [
        'Controller' => 'System\Core\Controller',
        'Model' => 'System\Core\Model',
        'Module' => 'System\Core\Module',
    ]    
];

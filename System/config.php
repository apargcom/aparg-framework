<?php

return [
    
    'db_charset' => 'utf8',
    'html_charset' => 'utf-8',
    'db_engine' => 'innoDB',

    'base_url' => 'http://' . $_SERVER['SERVER_NAME'],
    'base_path' => $_SERVER['DOCUMENT_ROOT'],

    'app_path' => $_SERVER['DOCUMENT_ROOT']."/App",
    'system_path' => $_SERVER['DOCUMENT_ROOT']."/System",
    
    'default_controller' => ($default_controller = "index"),
    
    'route_404' => $default_controller.'/default404',
    
    'routes' => [
    ],
    
    'min_php_version' => '5.3.0',

    'debug_mode' => false,

    'output_buffering' => true,
    
    'aliases'=> [
        //'Controller' => '\\System\Core\Controller',
        //'Config' => '\\\\System\Core\Config',
    ]
    
];
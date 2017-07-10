<?php
return [
    'DEFAULT_MODULE'=>'Home',
    'DEFAULT_CONTROLLER'=>'Index',
    'DEFAULT_ACTION'=>'index',
    //数据库配置
    'DB_CONFIG'=>[
        //'DB_TYPE' => 'mysqli',
         'DB_TYPE' => 'mysqli',
        'DB_HOST' => 'localhost',
        'DB_PORT' => '3306',
        'DB_USERNAME' => 'root',
        'DB_PASSWORD' => 'root',
        'DB_NAME' => 'sugaophp',
    ],
    'log' => [
        'type' =>'LogFile',
        'path' => 'Log/runtime',
    ]
];


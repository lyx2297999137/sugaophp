<?php

$config= [
    'HANDLE_DEBUG'=>false,   //异常记录
    'WHOOPS_DEBUG'=>true,   //编代码时的调试
    'URL_MODEL'=>1 , //url模式， 1 代表普通模式  2代表PATHINFO 模式
    'DEFAULT_MODULE' => 'Home',
    'DEFAULT_CONTROLLER' => 'Index',
    'DEFAULT_ACTION' => 'index',
    //数据库配置
    'DB_CONFIG' => [
        //'DB_TYPE' => 'mysqli',
//        'DB_TYPE' => 'mysqli',
//        'DB_HOST' => 'localhost',
//        'DB_PORT' => '3306',
//        'DB_USERNAME' => 'root',
//        'DB_PASSWORD' => 'root',
//        'DB_NAME' => 'sugaophp',
    ],
    //S方法的
    'log' => [
        'type' => 'LogFile',
        'path' => 'Log/runtime',
    ],
    //app钩子
    'app' => [
        'app_init' => [
//            '\\sugaophp\\Behavior\\appconfig',
//            '\\sugaophp\\Behavior\\appconfig2',
        ],
        'view_parse'    =>  array(
            '\\sugaophp\\Behavior\\ParseTemplateBehavior', // 模板解析 支持PHP、内置模板引擎和第三方模板引擎
        ),
    ],
    //
    'template' => [
        /**
         * 模版路径
         * @var string path
         */
        'path' => BASEDIR . '/View/',
        /**
         * 1 开启模版刷新, 0 关闭模版刷新
         * @var int refresh
         */
        'refresh' => 1,
        /**
         * 设置模板后缀
         * @var string suffix
         */
        'suffix' => 'html'
    ],
];
//return $config;

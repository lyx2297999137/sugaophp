<?php

// 系统信息
if (version_compare(PHP_VERSION, '5.4.0', '<')) {
    ini_set('magic_quotes_runtime', 0);
    define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc() ? true : false);
} else {
    define('MAGIC_QUOTES_GPC', false);
}
$arr_data = \sugaophp\Superglobal::$methods;
define('MODULE_NAME', $arr_data['module']);
define('CONTROLLER_NAME', $arr_data['controller']);
define('ACTION_NAME', $arr_data['action']);
define('APP_PATH', BASEDIR . '/App/');


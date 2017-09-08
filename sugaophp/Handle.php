<?php

namespace sugaophp;

/**
 * 注册模式
 */
class Handle {

    public function init() {
        $appDebug = Superglobal::$config['HANDLE_DEBUG'];
        if ($appDebug) {
            error_reporting(0);
            set_error_handler('\\sugaophp\\Handle::_errorHandler');  //我还想用self::_errorHandle的，结果不行
            //居然不会显示错误。而且下面的居然还执行，错误后面还能输出
//            dump('23432');
            set_exception_handler('\\sugaophp\\Handle::_exceptionHandler');
//            dump('44sa');
            register_shutdown_function('\\sugaophp\\Handle::_shutdownHandler');
        }
    }

    /**
     * 记录debug.log  type=error
     *
     * @return void
     */
    public static function _errorHandler($errno, $errstr, $errfile, $errline) {
        Factory::getInstance('handle_log')->debugInfo('error', $errno, $errfile, $errline, $errstr);
        return TRUE;
    }

    /* }}} */

    /**
     * 记录debug.log  type=exception
     *
     * @return void
     */
    public static function _exceptionHandler($e) {
        $class_name = get_class($e);
        $class_name = !empty($class_name) ? $class_name : 'exception';
        Factory::getInstance('handle_log')->debugInfo($class_name, $e->getCode(), $e->getFile(), $e->getLine(), $e->getMessage());
//        exit(0);
    }

    /**
     * 记录debug.log  type=shutdown
     *
     * @return void
     */
    public static function _shutdownHandler() {
        $e = error_get_last();
        if (!empty($e)) {
            Factory::getInstance('handle_log')->debugInfo('shutdown', $e['type'], $e['file'], $e['line'], $e['message']);
        }
//        exit(0);
    }

}

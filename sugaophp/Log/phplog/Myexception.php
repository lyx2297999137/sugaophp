<?php

namespace sugaophp\Log\phplog;

use ErrorException;
use Exception;

/**
 * 异常处理类
 */
class Myexception extends Exception {

    /**
     * 
            try {
                throw new Myexception($directory.'文件不存在或者文件不可写');
            } catch (Myexception $e) {
                Myexception::log($e);
            }
       
     * @param type $e
     */
    public static function log($e) {
        $file = $e->getFile();
        $line = $e->getLine();
        $code = $e->getCode();
        $message = $e->getMessage();
        my_error_log(array('file' => $file, 'line' => $line, 'code' => $code, 'message' => $message,'e'=>$e), 'log', 'log');
    }

    /**
     * @desc 	异常处理函数
     * @parm 	object $e 异常对象
     */
    public static function exceptionHandler($e) {
        $file = $e->getFile();
        $line = $e->getLine();
        $code = $e->getCode();
        $message = $e->getMessage();
        if (Startlog::$log != null) {
            if (class_exists(ConfigLog::$class_log, false) && !in_array($code, ConfigLog::$except_error)) {
                Log::$writeOnAdd = true;
                Startlog::$log->add($code, '[' . $code . ']:' . $message, array('file' => $file, 'line' => $line));
            }
        }
    }

    /**
     * @desc 	错误处理函数
     *
     */
    public static function errorHandler($errno, $errstr, $errfile, $errline) {
        self::exceptionHandler(new ErrorException($errstr, $errno, 0, $errfile, $errline));
    }

    /**
     *
     *
     */
    public static function shutdownHandler() {
        $error = error_get_last();
        if ($error) {
            self::exceptionHandler(new ErrorException($error['message'], $error['type'], 0, $error['file'], $error['line']));
        }
    }

}

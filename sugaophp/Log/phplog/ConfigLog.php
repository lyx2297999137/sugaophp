<?php
namespace sugaophp\Log\phplog;
class ConfigLog{
    static $namespace='sugaophp\Log\phplog';
    static $class_namespace="\\sugaophp\\Log\\phplog";//\\sugao_wxpay\\Phplog\\Log
    static $class_myexception="\\sugaophp\\Log\\phplog\\Myexception";
    static $class_log="\\sugaophp\\Log\\phplog\\Log";
    
    //存放路径
    public static $logpath="/View/Runtime/phplog";
    public static $real_logpath=BASEDIR."/View/Runtime/phplog";
    
    //错误级别
    //http://www.w3school.com.cn/php/php_ref_error.asp
//          1	E_ERROR	运行时致命的错误。不能修复的错误。终止执行脚本。
//          2	E_WARNING	运行时非致命的错误。不终止执行脚本。
//          4	E_PARSE	编译时语法解析错误。解析错误仅仅由分析器产生。
//          8	E_NOTICE	运行时通知。表示脚本遇到可能会表现为错误的情况，但是在可以正常运行的脚本里面也可能会有类似的通知。
//          16	E_CORE_ERROR	在 PHP 初始化启动过程中发生的致命错误。该错误类似 E_ERROR，但是是由 PHP 引擎核心产生的。
//          32	E_CORE_WARNING	PHP 初始化启动过程中发生的警告 (非致命错误) 。类似 E_WARNING，但是是由 PHP 引擎核心产生的。
//          64	E_COMPILE_ERROR	致命编译时错误。类似 E_ERROR, 但是是由 Zend 脚本引擎产生的。
//          128	E_COMPILE_WARNING	编译时警告 (非致命错误)。类似 E_WARNING，但是是由 Zend 脚本引擎产生的。
//          256	E_USER_ERROR	用户产生的错误信息。类似 E_ERROR, 但是是由用户自己在代码中使用PHP函数 trigger_error()来产生的。
//          512	E_USER_WARNING	用户产生的警告信息。类似 E_WARNING, 但是是由用户自己在代码中使用 PHP 函数 trigger_error() 来产生的。
//          1024	E_USER_NOTICE	用户产生的通知信息。类似 E_NOTICE, 但是是由用户自己在代码中使用 PHP 函数 trigger_error() 来产生的。
//          2048	E_STRICT	启用 PHP 对代码的修改建议，以确保代码具有最佳的互操作性和向前兼容性。
//          4096	E_RECOVERABLE_ERROR	可被捕捉的致命错误。它表示发生了一个可能非常危险的错误，但是还没有导致 PHP 引擎处于不稳定的状态。 如果该错误没有被用户自定义句柄捕获 (参见 set_error_handler())，将成为一个 E_ERROR 从而脚本会终止运行。
//          8192	E_DEPRECATED	运行时通知。启用后将会对在未来版本中可能无法正常工作的代码给出警告。
//          16384	E_USER_DEPRECATED	用户产生的警告信息。类似 E_DEPRECATED, 但是是由用户自己在代码中使用 PHP 函数 trigger_error() 来产生的。
//          2767	E_ALL	E_STRICT 除非的所有错误和警告信息。
    public static $except_error=array(E_DEPRECATED);
}


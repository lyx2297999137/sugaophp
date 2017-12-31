<?php
namespace sugaophp\Log\phplog;

class Startlog{
	/**
	 * @var object $log 保存Log对象实例
	 */
	public static $log;
       
	/**
	 * 
	 */
	public static function go(){
                $logpath= ConfigLog::$logpath;
                $realpath_logpath= ConfigLog::$real_logpath;
                if(!is_dir($logpath)){
                    WSTCreateDir($logpath);
                }
		self::$log = Log::instance();
		self::$log->attach(new CreateLogFile($realpath_logpath),array());
//                self::$log->attach(new CreateLogFile($realpath_logpath),array(E_NOTICE));
		set_exception_handler(array(ConfigLog::$class_myexception,"exceptionHandler"));
		set_error_handler(array(ConfigLog::$class_myexception,"errorHandler"));
		//设置一个程序异常终止的时候的错误处理函数
		register_shutdown_function(array(ConfigLog::$class_myexception,"shutdownHandler"));
	}
}
//\sugaophp\Log\phplog\Startlog::go();
//echo $a[a];//notice
//settype($var,'ee');//warning
//md6(1);//fatal

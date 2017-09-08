<?php
namespace sugaophp\Log;
/**
 * 日志收集 
 *
 * 以JSON串格式存入到log文本文件, 排查问题必备
 *
 * 日志文件名按天取名, 文本文件格式:  xxx_log.ymd
 *
 */
class HandleLog
{
    /** @var string $full_path 日志收集目录 */
    public $full_path = null;

    /** @var string $site_file_name 相当于access.log, 但这个日志是个性化的记录, 可以自定义文本文件名称 */
    public $site_file_name 	 = 'website_log';

    /** @var string $sql_file_name 线上、线下都建议记录, 可以自定义文本文件名称 */
    public $sql_file_name  	 = 'sql_log';

    /** @var string $debug_file_name 调试环境下使用, 可以自定义文本文件名称 */
    public $debug_file_name  = 'debug_log';

    /**
     * 所有日志路径的初始化
     *
     * @return viod
     */
    public function __construct()
    {/*{{{*/
        if(!$this->full_path)
        {
            $this->full_path = BASEDIR . DIRECTORY_SEPARATOR . 'View/Runtime/HandleLogs';
        }
        
        if(!is_dir($this->full_path))
        {
            if(@mkdir($this->full_path) === FALSE)
            {
                trigger_error("{$this->full_path} mkdir failed!", E_USER_ERROR);
            }
        }
    }/*}}}*/

    /**
     * website访问日志记录
     *
     * @param string $msg 记录的内容 
     * @return void
     */
    public function siteInfo($reponse = '')
    {/*{{{*/
        $info = '';
        $data = array();

        $ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1';
        if(!empty($reponse))
        {
            $data['reponse'] = $reponse;
        }

        $data['get'] = isset($_GET) ? $_GET : '';
        $data['post'] = isset($_POST) ? $_POST : '';
//        $data['cookie'] = isset($_COOKIE) ? $_COOKIE : '';
        $data['server_url'] = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; //当前url
//        $data['server_gettime'] = $_SERVER['REQUEST_TIME_FLOAT'];

        $info  = '[LOG_TIME] '. date('Y-m-d H:i:s') . "\t";
        $info .= "[IP] {$ip} \t";
        $info .= "[SITE] " . json_encode($data);
        $info .= '[*]lineEnd[*]';
        error_log($info . PHP_EOL, 3, $this->full_path . DIRECTORY_SEPARATOR . $this->site_file_name. '.' . date('Ymd'));
    }/*}}}*/

       /* 保存json返回数据 
        * xiaoliao
        */
    private function set_json_data($data, $json_option) {
        //记录请求东西
        //$destination = $_SERVER['DOCUMENT_ROOT'] . '/Uploads/apilog.txt';
        $destination = C('LOG_PATH') . 'api_post_temp.log';
        $log_dir = dirname($destination);
        if (!is_dir($log_dir)) {
            mkdir($log_dir, 0755, true);
        }
        $init_app_arr = $_POST;
        $init_app_arr['server_url'] = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; //当前url
        //$microtime = $this->microtime_float();
        //$init_app_arr['server_gettime'] = $this->microtime_format('Y-m-d H:i:s x毫秒', $microtime);
        $init_app_arr['server_gettime'] = $_SERVER['REQUEST_TIME_FLOAT'];
        $init_app_arr['json'] = json_encode($data, $json_option);
        error_log(serialize($init_app_arr), 3, $destination);
    }
    /**
     * sql日志记录(MySQL)
     *
     * @param string $sql SQL命令
     * @param int $time SQL执行的时间
     * @return void
     */
    public function sqlInfo($sql, $time)
    {/*{{{*/
        $ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1';
        $info  = '[LOG_TIME] '. date('Y-m-d H:i:s') . "\t";
        $info .= "[IP] {$ip} \t";
        $info .= "[EXE_TIME] {$time} \t";
        $info .= "[SQL] {$sql}";

        error_log($info . PHP_EOL, 3, $this->full_path . DIRECTORY_SEPARATOR . $this->sql_file_name. '.' . date('Ymd'));
    }/*}}}*/

    /**
     * debug日志记录, 开启之后排查问题变得很轻松
     *
     * @param string $type
     * @param int $errno
     * @param string $file
     * @param int $line
     * @param string $msg
     *
     * @return void
     */
    public function debugInfo($type, $errno, $file, $line, $msg)
    {/*{{{*/
        $info = '';
        $data = array();

        $ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1';
        $info  = '[LOG_TIME] '. date('Y-m-d H:i:s') . "\t";
        $info .= "[IP] {$ip} \t";

        $data['status'] = $type;
        $data['get'] = isset($_GET) ? $_GET : '';
        $data['post'] = isset($_POST) ? $_POST : '';
        $data['cookie'] = isset($_COOKIE) ? $_COOKIE : '';

        $data['info']['errno'] = $errno;
        $data['info']['file'] = $file;
        $data['info']['line'] = $line;
        $data['info']['msg']  = $msg;

        $info .= "[DEBUG] " . json_encode($data);

        error_log($info . PHP_EOL, 3, $this->full_path . DIRECTORY_SEPARATOR . $this->debug_file_name. '.' . date('Ymd'));
    }/*}}}*/
}

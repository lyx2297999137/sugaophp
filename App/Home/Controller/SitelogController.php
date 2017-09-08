<?php

namespace App\Home\Controller;
use sugaophp\Template;
//http://www.sugaophp12.com/?controller=sitelog&action=index
//http://www.sugaophp12.com/?controller=sitelog&action=delete_file
class SitelogController  extends Template{

    function index($path = null) {
        //文件路径
        if ($path == null) {
            $file_path = BASEDIR . DIRECTORY_SEPARATOR . 'View/Runtime/HandleLogs/website_log.' . date('Ymd');
        } else {
            $file_path = $path;
        }
        if(!is_file($file_path)){
            die('今天没有访问记录');
        }
        $log_content = file_get_contents($file_path);
        $log_content_arr = explode("[*]lineEnd[*]", $log_content);
        array_pop($log_content_arr);
        $show_arr = [];
        foreach ($log_content_arr as $key => $one_log_str) {
            $logtime_length = strlen('[LOG_TIME]');
            $ip_length = strlen('[IP]');
            $site_length = strlen('[SITE]');
            $logtime_position = stripos($one_log_str, '[LOG_TIME]');
            $ip_position = stripos($one_log_str, '[IP]');
            $site_position = stripos($one_log_str, '[SITE]');
            $show_arr[$key]['log_time'] = substr($one_log_str, $logtime_position + $logtime_length, $ip_position - $logtime_position - $logtime_length - 1);
            $show_arr[$key]['ip'] = substr($one_log_str, $ip_position + $ip_length, $site_position - $ip_position - $ip_length - 1);
            $show_arr[$key]['site'] = substr($one_log_str, $site_position + $site_length);
        }
        $this->assign('show_arr',$show_arr);
        $key_arr=isset($show_arr[0])?array_keys($show_arr[0]):[];
	 $this->assign('key_arr',$key_arr);
		// 读取模版
		$this->display();
    }
    
        //删除文件
    public function delete_file() {
        $unlink_path = BASEDIR . DIRECTORY_SEPARATOR . 'View/Runtime/HandleLogs/website_log.' . date('Ymd');
        $unlinkm = unlink($unlink_path);
        if ($unlinkm == true) {
            echo json_encode(['rs_code'=>1000]);
        } else {
            echo json_encode(['rs_code'=>999]);
        }
    }
}

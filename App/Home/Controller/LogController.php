<?php

namespace App\Home\Controller;
//http://www.sugaophp12.com/?controller=log&action=index
//http://www.sugaophp12.com/?controller=log&action=dump
class LogController {
    function index(){
        $this->log('111','222',[1,2,3]);die;
        dump(1111);die;
    }
     /**
     * 公共的记录日志方法，日志存储方式由配置文件决定
     * @param $fileName
     * @param string $content
     * @param array $array
     * @return mixed
     */
    public function log($fileName, $content = '', $array = []){
        $logConfig = \sugaophp\Config::getConfig('log');
        dump($logConfig);
        $logObj = "\\sugaophp\\Log\\" . $logConfig['type'];
        return (new $logObj)->log($fileName, $content, $array);
    }
}

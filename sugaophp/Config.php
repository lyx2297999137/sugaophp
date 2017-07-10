<?php

namespace sugaophp;

class Config {

    public static $config;

    public static function init() {
        if (empty(self::$config)) {
//            dump('config');  //为什么我感觉永远会执行到这里啊。难道是因为静态方法 2017年6月2日11:11:36 
            self::$config = require_once BASEDIR . '/Common/Config/config.php'; //这个牛逼啊。直接就返回了  
        }
        return self::$config;
    }
   
    /**
     * 读取配置
     * @param type $name
     * @return type
     * @throws \Exception
     */
    public static function getConfig($name = '')
    {
        if(!self::$config){
            self::init();
        }
        if($name){
            if (isset(self::$config[$name])){
                return self::$config[$name] ;
            }
        }
        throw new \Exception('没有该项配置，请检查配置文件');
    }

}

<?php

namespace sugaophp;

class Config {
    public $config;

    public function init(){
        $this->config=require_once BASEDIR.'/Common/Config/config.php';//这个牛逼啊。直接就返回了
        return $this->config;
    }
}

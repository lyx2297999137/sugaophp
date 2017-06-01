<?php

namespace sugaophp;
class App {
    public function init(){
        $this->require_base();
        $this->route();
    }
    /**
     * 引入基本的文件
     */
    private function require_base(){
        require_once BASEDIR.'/Common/Common/function.php';
//        $this->config=require_once BASEDIR.'/Common/Config/config.php';这个牛逼啊。直接就返回了
    }
    /**
     * 路由
     */
    private function route(){
        $route=new Route();
        $route->parse();
    }
}

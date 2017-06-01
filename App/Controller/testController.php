<?php

namespace App\Controller;
//http://www.sugaophp12.com/?controller=test&action=db
class testController {

    function test() {
        dump('test');
    }
    //**测试数据库连接
    public function dbtest(){
        $mysqli=new \sugaophp\Db\Mysqli\MySqli();
                $query = "
             CREATE TABLE IF NOT EXISTS `user`(                               
                     `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID', 
                     `name` varchar(32) NOT NULL COMMENT '姓名',                   
                     `age` smallint(3) NOT NULL COMMENT '年龄',                    
                     PRIMARY KEY (`id`)                                                                    
                   )ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='用户信息';
        ";
        $mysqli->query($query);
    }

}

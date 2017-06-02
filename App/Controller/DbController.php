<?php

namespace App\Controller;
//http://www.sugaophp12.com/?controller=db&action=dbinsert
class DbController {
    //**测试数据库连接  。创建数据表
    public function dbcreate(){
        $mysqli=new \sugaophp\Db\Mysqli\MySqli();
                $query = "
             CREATE TABLE IF NOT EXISTS `user2`(                               
                     `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID', 
                     `name` varchar(32) NOT NULL COMMENT '姓名',                   
                     `age` smallint(3) NOT NULL COMMENT '年龄',                    
                     PRIMARY KEY (`id`)                                                                    
                   )ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='用户信息';
        ";
        $mysqli->query($query);
    }
    //插入记录
    public function dbinsert(){
        $mysqli=new \sugaophp\Db\Mysqli\MySqli();
        $query="
            insert into `user`(`name`,`age`) values('a',20),('b',18),('c',19);
                ";
        $mysqli->query($query);
    }
    //查询数据
    public function dbselect(){
        $mysqli=new \sugaophp\Db\Mysqli\MySqli();
        $query="
            select * from user;
                ";
        $rows=$mysqli->select($query);
        dump($rows);
    }
    //修改
    public function dbupdate(){
        $mysqli=new \sugaophp\Db\Mysqli\MySqli();
        $query="
            update user set age=100 where id= 18;
                ";
        $rows=$mysqli->query($query);
        dump($rows);
    }
    //删除
    public function dbdelete(){
        $mysqli=new \sugaophp\Db\Mysqli\MySqli();
        $query="
            delete from user where id= 18;
                ";
        $rows=$mysqli->query($query);
        dump($rows);
    }
}

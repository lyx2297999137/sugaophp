<?php

namespace sugaophp\Db\Mysqli;

class MySqli {

    public $mysqli_con;

    public function __construct() {
        if (empty($this->mysqli_con)) {
             //mysqli 连接
            $con = new \mysqli('localhost', 'root', 'root', 'sugaophp');
//          $con=@new mysqli('localhost','root','root','sugaophp');//这个也没用啊。垃圾。你自己才垃圾。命名空间的问题
//          $con=mysqli_connect("localhost","root","root","sugaophp");//这个可以用。面向过程的
            if ($con->connect_error) {
                dump($con->connect_error);
                die;
            }
            $con->set_charset('utf8');
            $this->mysqli_con = $con;
        }
    }
    //析构函数
    public function __destruct() {
        $this->close();
    }
    //关闭连接
    public function close(){
        //关闭到MySQL的连接
        $this->mysqli_con->close();
    }
    //mysqli query查询。执行
    public function query($query) {
//        $query = "
//             CREATE TABLE IF NOT EXISTS `user`(                               
//                     `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID', 
//                     `name` varchar(32) NOT NULL COMMENT '姓名',                   
//                     `age` smallint(3) NOT NULL COMMENT '年龄',                    
//                     PRIMARY KEY (`id`),                                                                    
//                   )ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='用户信息';
//        ";
        $res = $this->mysqli_con->query($query);
        if (!$res) {
            dump($this->mysqli_con->error);
            die;
        }
    }

}

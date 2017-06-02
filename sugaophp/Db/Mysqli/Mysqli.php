<?php

namespace sugaophp\Db\Mysqli;

class MySqli {

    public $mysqli_con;

    public function __construct() {
        if (empty($this->mysqli_con)) {
            //mysqli 连接
            $config = \sugaophp\Config::init();
//            $con = new \mysqli('localhost', 'root', 'root', 'sugaophp');
            $con = new \mysqli($config['DB_CONFIG']['DB_HOST'], $config['DB_CONFIG']['DB_USERNAME'], $config['DB_CONFIG']['DB_PASSWORD'], $config['DB_CONFIG']['DB_NAME']);
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
    public function close() {
        //关闭到MySQL的连接
        $this->mysqli_con->close();
    }

    //mysqli query执行
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
        return $res;
    }

    public function select($sql) {
//        $sql = "SELECT id,username,age FROM user";
        $mysqli_result = $this->mysqli_con->query($sql);
//var_dump($mysqli_result);
        if ($mysqli_result && $mysqli_result->num_rows > 0) {
            //echo $mysqli_result->num_rows;
            //$rows=$mysqli_result->fetch_all();//获取结果集中所有记录，默认返回的是二维的
            //索引+索引的形式
            //$rows=$mysqli_result->fetch_all(MYSQLI_NUM);
            //$rows=$mysqli_result->fetch_all(MYSQLI_ASSOC);
            //$rows=$mysqli_result->fetch_all(MYSQLI_BOTH);
// 	$row=$mysqli_result->fetch_row();//取得结果集中一条记录作为索引数组返回
// 	print_r($row);
// 	echo '<hr/>';
// 	$row=$mysqli_result->fetch_assoc();//取得结果集中的一条记录作为关联数组返回
// 	print_r($row);
// 	echo '<hr/>';
// 	$row=$mysqli_result->fetch_array();
// 	print_r($row);
// 	echo '<hr/>';
// 	$row=$mysqli_result->fetch_array(MYSQLI_ASSOC);
// 	print_r($row);
// 	echo '<hr/>';
// 	$row=$mysqli_result->fetch_object();
// 	print_r($row);
// 	echo '<hr/>';
// 	//移动结果集内部指针
// 	$mysqli_result->data_seek(0);
// 	$row=$mysqli_result->fetch_assoc();
// 	print_r($row);
// 	print_r($rows);

            while ($row = $mysqli_result->fetch_assoc()) {
                //print_r($row);
                //echo '<hr/>';
                $rows[] = $row;
            }
            //释放结果集
            $mysqli_result->free();
            return $rows;
        } else {
            echo '查询错误或者结果集中没有记录';
        }
    }

}

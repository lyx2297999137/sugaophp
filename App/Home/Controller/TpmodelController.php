<?php

namespace App\Home\Controller;

use sugaophp\Db\Orm\Db;

//http://www.sugaophp12.com/?controller=tpmodel&action=add
//http://www.sugaophp12.com/?controller=tpmodel&action=update
//http://www.sugaophp12.com/?controller=tpmodel&action=read
//http://www.sugaophp12.com/?controller=tpmodel&action=delete
//http://www.sugaophp12.com/?controller=tpmodel&action=ar
/**
 * 仿照tp写的 orm
 */
class TpmodelController {

    /**
     * 数据库准备
      CREATE TABLE `user` (
      `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
      `name` varchar(20) NOT NULL DEFAULT '',
      `age` smallint(3) NOT NULL,
      `email` varchar(64) DEFAULT NULL,
      PRIMARY KEY (`id`)
      ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8
     */
    //新增
    public function add() {
        $user = D("user"); // 实例化User对象
        dump($user);
        $info['name'] = '张三23';
        $info['email'] = '2297999137@gmail.com';
        dump('user-add-result');
        if ($user->create($info)) {

            $result = $user->add(); // 写入数据到数据库 
            if ($result) {
                // 如果主键是自动增长型 成功后返回值就是最新插入的值 $insertId = $result;
            }
            dump($result);
        } else {
            dump($user->getError());
            dump('create_faild');
        }
    }

    //修改
    public function update() {
        ///////////////////////////
        //////////一般的//////////
        ///////////////////////////
        $user = D("User"); // 实例化User对象
        // 要修改的数据对象属性赋值
        $info['name'] = '张三233';
        $info['email'] = '2297999137@gmail.com';
        $save_result = $user->where('id=24')->save($info); // 根据条件更新记录
        dump($save_result);
        $setfield = $user->where('id=25')->setField(array('age' => 25));
        dump($setfield);
        $setinc = $user->where(array('id' => 26))->setInc('age', 2); //setdec
        dump($setinc);
        ///////////////////////////
        //////////create的//////////
        ///////////////////////////
//        $user = D("user"); // 实例化User对象
//        dump($user);
//        $info['name'] = '张三233';
//        $info['email'] = '2297999137@gmail.com';
//        dump('user-add-result');
//        if ($user->create($info)) {  //有这create就会检查$_validate
//            $result = $user->where(array('id' => 24))->save(); // 写入数据到数据库 
//            dump($result);
//        } else {
//            dump($user->getError());
//            dump('update_faild');
//        }
    }

    //读取
    function read() {
        $user = D("User"); // 实例化User对象
        ///////////////////////////
        //////////读取数据//////////
        ///////////////////////////
        $find = $user->where(array('id' => 24))->find();
        dump($find);
        dump($user->data()); //和dump($find)一样
        ///////////////////////////
        //////////读取数据集//////////
        ///////////////////////////
        $select = $user->where(array('age' => 16))->select();
        dump($select);
        ///////////////////////////
        //////////读取字段值//////////
        ///////////////////////////
        $getfield = $user->where(array('id' => 24))->getField('email');
        dump($getfield); //只有一个，字符串
        $getfield = $user->getField('email');
        dump($getfield); //只有一个，字符串
        $getfield = $user->getField('email', true);
        dump($getfield); //有全部，三个，索引数组
        $getfield = $user->getField('email', 2);
        dump($getfield); //有两个，索引数组
        $getfield = $user->where(array('id' => 24))->getField('name,email');
        dump($getfield);  //得到name=>email
        $getfield = $user->where(array('id' => 24))->getField('name,email,id');
        dump($getfield);  //得到的居然是name=>array('name'=>'xxx','email'=>'yy','id'=>'zz')
        $getfield = $user->where(array('id' => 24))->getField('name,email,id', ':');
        dump($getfield);  //得到的居然是email_str:id_str
    }

    //删除
    function delete() {
        $user = D("User");
        $delete_result = $user->delete(24);
        dump($delete_result); //1
        $delete_result = $user->delete('25,26');
        dump($delete_result); //2
        $delete_result = $user->where(array('age' => 24))->delete();
        dump($delete_result); //1
    }

    //ThinkPHP实现了ActiveRecords模式的ORM模型，采用了非标准的ORM模型：表映射到类，记录映射到对象
    function ar() {
        ///////////////////////////
        //////////创建数据//////////
        ///////////////////////////
        $User = D("User");
        $User->create(); // 创建User数据对象，默认通过表单提交的数据进行创建
        // 增加或者更改其中的属性
        $User->name = 1;
        $User->email = 'erere';
        // 把数据对象添加到数据库
        $add_result = $User->add();
        dump($add_result); //为啥age没有自动加上呢？2018年3月2日17:23:39
        ///////////////////////////
        //////////查询记录//////////
        ///////////////////////////
        $find = $User->find(27);
        dump($find);
        $select = $User->select('27,29');
        dump($select);
        ///////////////////////////
        //////////更新记录//////////
        ///////////////////////////
        $User->id = 27;
        $User->name = 'TOPThink'; // 修改数据对象
        $save = $User->save(); // 保存当前数据对象
        dump($save);
        ///////////////////////////
        //////////删除记录//////////
        ///////////////////////////
        //就是上面的delete()
    }

}

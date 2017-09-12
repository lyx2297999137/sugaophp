<?php

namespace App\Home\Controller;
use sugaophp\Db\Orm\Db;
//http://www.sugaophp12.com/?controller=orm&action=dbfindOne
//http://www.sugaophp12.com/?controller=orm&action=activeRecord
class OrmController {
    //查询数据
     public function dbfindAll(){
        $db=new Db();
        //$result=$db->table('user')->select(['id','name','age'])->where(['id'=>19,'name'=>'b'])->findAll();
        $result=$db->table('user')->where(['id'=>19,'name'=>'b'])->select(['name','age'])->findAll();
        dump($result);
    }
    
    public function dbfindOne(){
        $db=new Db();
        $result=$db->table('user')->select(['id','name','age'])->findOne();
        dump($result);
    }
    
    //插入记录
    public function dbinsert(){
        
    }
    public function activeRecord(){
        $user =new \App\Home\Model\User();
        $user->name='张三';
        $user->age='20';
        $result=$user->save();
        dump($result);
    }
}

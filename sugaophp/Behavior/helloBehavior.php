<?php
namespace sugaophp\Behavior;
class helloBehavior{
    // 行为扩展的执行入口必须是run
    public function run(&$params){
        // 开启静态缓存
//       echo 'hello'.$params['name'].$params['age'].'</br>';
       echo 'hello'.$params.'</br>';
//       exit();
    }
}


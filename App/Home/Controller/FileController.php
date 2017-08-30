<?php

namespace App\Home\Controller;
//http://www.sugaophp12.com/?controller=file&action=index
//http://www.sugaophp12.com/?controller=file&action=test
class FileController {
    public function index(){
        S('index','a',10);
    }
    public function test(){
       $index=S('index');
        dump($index);
    }
            function index1(){
         $fileModel=new \sugaophp\Cache\File();
         $fileModel->set('a',100,10); //有效时间10秒钟
         $fileModel->set('b',300);
    }
    function test1(){
        $fileModel=new \sugaophp\Cache\File();
        echo $fileModel->get('a');
        echo $fileModel->get('b');
    }
}

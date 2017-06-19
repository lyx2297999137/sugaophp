<?php

namespace App\Home\Controller;

class IndexController {
    function index(){
        $getui= new \sugao2013\composer_test\Test();
        $getui->test();
        echo '</br>';
        $getui->class_test();
//        dump('index');
    }
}

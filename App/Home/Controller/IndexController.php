<?php

namespace App\Home\Controller;
//http://www.sugaophp12.com/?controller=index&action=index
//http://www.sugaophp12.com/?controller=index&action=dump
class IndexController {
    function index(){
        $a=[];
        if($a['a']=='a'){
            echo '111';
        }
    }
    function dump(){
        dump('dump');
    }
}

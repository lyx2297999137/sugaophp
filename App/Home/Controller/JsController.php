<?php
namespace App\Home\Controller;
use sugaophp\Template;
//http://www.sugaophp12.com/?controller=js&action=index
//http://www.sugaophp12.com/?controller=js&action=dumpuse
class JsController extends Template{
    function index(){
        $this->display();
    }
    function dump(){
        dump('dump');
    }
}

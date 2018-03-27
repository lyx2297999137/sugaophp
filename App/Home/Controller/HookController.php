<?php
/**
 * 模仿tp hook 钩子 行为
 * sugao
 */
namespace App\Home\Controller;
use sugaophp\Hook;
//http://www.sugaophp12.com/?controller=hook&action=index
//http://www.sugaophp12.com/?controller=hook&action=addons_siteinfo
class HookController {
    function index(){
        Hook::add('hello','\\sugaophp\\Behavior\\helloBehavior');
//        Hook::listen('hello',['name'=>'张三','age'=>12]);
        $name='张三';
        Hook::listen('hello',$name);
        echo 'index</br>';
    }
    function addons_siteinfo(){
         Hook::add('siteInfo','\\App\\Addons\\SiteInfo\\SiteInfoController');
         Hook::listen('siteInfo');
    }
}

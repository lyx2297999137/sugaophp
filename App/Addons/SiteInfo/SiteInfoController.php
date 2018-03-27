<?php

namespace App\Addons\SiteInfo;
//http://www.sugaophp12.com/?module=Addons&controller=siteInfo&action=siteInfo
use Common\Addons\Addon;
class SiteInfoController extends Addon{
//    function a(){ //Common/function.php居然没有引进来
//        echo echo666();
//    }
    function siteInfo(){
        $this->display('index');
    }
    function install() {
        ;
    }
    function uninstall() {
        ;
    }
}

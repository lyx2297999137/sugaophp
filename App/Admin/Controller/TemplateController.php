<?php

namespace App\Admin\Controller;
//http://www.sugaophp12.com/?module=Admin&controller=template&action=index
use sugaophp\Template;
class TemplateController extends Template{
    function index(){
        /*{{{*/
                dump('admin');
		// assign 模版内变量赋值
		$this->assign('a', 1);
		$this->assign('data', array('user' => 'zhangsan', 'pass' => 123456));
		$this->assign('name', 'xiaofan');
	
		// 读取模版
		$this->display();
	/*}}}*/
    }
}

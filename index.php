<?php
//require_once 'b.php';
//require_once 'sugaophp/autoLoad.php';
define('BASEDIR',__DIR__);
require_once __DIR__.'/sugaophp/autoLoad.php';
//require_once __DIR__.'/sugaophp/function.php';
spl_autoload_register("\\sugaophp\\autoLoad::autoload");
//$a=new sugaophp\a();
//$a->a();
//$test=new App\Controller\testController();
//$test->test();

//http://www.sugaophp12.com/?controller=test&action=test
//$module=$_GET['module'];
//$controller=$_GET['controller'];
//$action=$_GET['action'];
//$controllerstr='App\\Controller\\'.$controller.'Controller';
//$test=new $controllerstr();
//$test->$action();
$app=new sugaophp\App();
$app->init();
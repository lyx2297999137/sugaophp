<?php
namespace sugaophp;
class Route{
    function parse(){
//        $module=$_GET['module'];
        $config=Config::init();
        $controller=isset($_GET['controller'])?$_GET['controller']:$config['DEFAULT_CONTROLLER'];
        $action=isset($_GET['action'])?$_GET['action']:$config['DEFAULT_ACTION'];
        $controllerstr='App\\Controller\\'.$controller.'Controller';
        $controllerModel=new $controllerstr();
        $controllerModel->$action();
    }
}

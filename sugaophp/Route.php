<?php
namespace sugaophp;
class Route{
    function parse(){
//        $module=$_GET['module'];
        $controller=$_GET['controller'];
        $action=$_GET['action'];
        $controllerstr='App\\Controller\\'.$controller.'Controller';
        $controllerModel=new $controllerstr();
        $controllerModel->$action();
    }
}

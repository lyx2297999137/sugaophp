<?php
namespace sugaophp;
class Route{
    function parse(){
//        $module=$_GET['module'];
//        $config=Config::init();
//        $config= Factory::getInstance('config');
        $config = Superglobal::$config;
//        dump($config);
        $module=isset($_GET['module'])?$_GET['module']:$config['DEFAULT_MODULE'];
        $controller=isset($_GET['controller'])?$_GET['controller']:$config['DEFAULT_CONTROLLER'];
        $action=isset($_GET['action'])?$_GET['action']:$config['DEFAULT_ACTION'];
        $controllerstr='App\\'.$module.'\\'.'Controller\\'.$controller.'Controller';;
        Superglobal::$methods=array('module'=>$module,'controller' => $controller, 'action' => $action);
        $controllerModel=new $controllerstr();
        $controllerModel->$action();
    }
}

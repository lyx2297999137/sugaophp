<?php

namespace sugaophp;

class Route {

    //执行url路径
    function parse() {
        $this->app_init();
        $config = Superglobal::$config;
        $module = isset($_GET['module']) ? $_GET['module'] : $config['DEFAULT_MODULE'];
        $controller = isset($_GET['controller']) ? $_GET['controller'] : $config['DEFAULT_CONTROLLER'];
        $action = isset($_GET['action']) ? $_GET['action'] : $config['DEFAULT_ACTION'];
        $controllerstr = 'App\\' . $module . '\\' . 'Controller\\' . $controller . 'Controller';
        Superglobal::$methods = array('module' => $module, 'controller' => $controller, 'action' => $action);
        $controllerModel = new $controllerstr();
        $controllerModel->$action();
    }

    public function app_init() {
        $this->app_config_parse('app_init');
    }

    /**
     * 根据config配置，app初始化执行
     */
    public function app_config_parse($param = '') {
        if ($param) {
            $app_configs = Superglobal::$config['app'];
            $app_init_config = $app_configs[$param];
            if (is_array($app_init_config)) {
                foreach ($app_init_config as $app_init_config_one) {
                    Hook::add($param, $app_init_config_one);
                }
            } else {
                Hook::add($param, $app_init_config);
            }
            $name = '张三';
            Hook::listen($param, $name);
        }

//        if ($param) {
//            $app_configs = Superglobal::$config['app'];
//            $app_init_config = $app_configs[$param];
//            if (is_array($app_init_config)) {
//                foreach ($app_init_config as $app_init_config_one) {
//                    list($method, $action) = explode('.', $app_init_config_one);
//                    $class = new $method();
//                    $class->$action();
//                }
//            } else {
//                list($method, $action) = explode('.', $app_init_config);
//                $class = new $method();
//                $class->$action();
//            }
//        }
    }

}

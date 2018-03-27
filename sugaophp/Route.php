<?php

namespace sugaophp;

class Route {

    //执行url路径
    function parse() {
        $this->app_init();
        $config = Superglobal::$config;
        if ($config['URL_MODEL'] == 1) {
            $module = isset($_GET['module']) ? $_GET['module'] : $config['DEFAULT_MODULE'];
            $controller = isset($_GET['controller']) ? $_GET['controller'] : $config['DEFAULT_CONTROLLER'];
            $action = isset($_GET['action']) ? $_GET['action'] : $config['DEFAULT_ACTION'];
            if ($module == 'Addons') {
                $controllerstr = 'App\\' . $module . '\\' . $controller . '\\' . $controller . 'Controller';
            } else {
                $controllerstr = 'App\\' . $module . '\\' . 'Controller\\' . $controller . 'Controller';
            }
            Superglobal::$methods = array('module' => $module, 'controller' => $controller, 'action' => $action);
            require_once BASEDIR . '/sugaophp/define.php';
            $controllerModel = new $controllerstr();
            $controllerModel->$action();
        } else if ($config['URL_MODEL'] == 2) {
            $request_uri = $_SERVER['REQUEST_URI'];
            $uri_arr = explode('/', $request_uri);
            $controller = $uri_arr[1];
            $action = $uri_arr[2];
            $controllerstr = 'App\\Api\\' . 'Controller\\' . $controller . 'Controller';
            $controllerModel = new $controllerstr();
            $controllerModel->$action();
        }
    }

    public function app_init() {
        $this->app_config_parse('app_init');
        $this->app_config_parse('view_parse');
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
        }
    }

}

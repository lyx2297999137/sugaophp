<?php

namespace sugaophp;

use Whoops\Handler\PrettyPageHandler;
use Whoops\Handler\JsonResponseHandler;
use sugaophp\Log\phplog\Startlog;
class App {

    private $config;

    public function init() {
        $this->_setDefaultTimezone();
        $this->require_base();
        $this->whoops();
        $this->handle();
        $this->sitelog();
        $this->route();
    }

    /**
     * 引入基本的文件
     */
    private function require_base() {
        require_once BASEDIR . '/Common/Common/function.php';
        require_once BASEDIR . '/sugaophp/function.php';
        require_once BASEDIR . '/Common/Config/config.php';
        //这个弄成全局的config?
        Superglobal::$config = $config;
        $this->config = $config;
    }

    /**
     * 路由
     */
    private function route() {
        $route = new Route();
        $route->parse();
    }

    /**
     * 异常记录以及请求参数记录
     * trigger_error这个函数是什么？
     * author sugao
     */
    private function handle() {
        $route = new Handle();
        $route->init();
    }

    /**
     * 记录请求信息
     * xiaoliao 
     */
    private function sitelog() {
        if ($this->config['HANDLE_DEBUG']) {
            Superglobal::$inputs = array(
                'get' => $_GET,
                'post' => $_POST,
                'cookie' => $_COOKIE
            );
            if (isset($_GET['controller']) && $_GET['controller'] === 'sitelog') { // && !isset($_GET['s'])
            } elseif (isset($_GET['s']) && $_GET['s'] === "\/favicon.ico") {  //这个暂时无效，得想解决方法
            } else {
                Factory::getInstance('handle_log')->siteInfo();
            }
        }
    }

    /**
     * 注意事项： 这个必须在路由之前($this->route())
     * debug工具
     * http://filp.github.io/whoops/
     */
    private function whoops() {
        if ($this->config['WHOOPS_DEBUG']) {
            $run = new \Whoops\Run;
            $handler = new PrettyPageHandler;

            $handler->setPageTitle("苏羔,你这里有一个问题啊.");
            $run->pushHandler($handler);

            if (\Whoops\Util\Misc::isAjaxRequest()) {
                $run->pushHandler(new JsonResponseHandler);
            }

            $run->register();
        }else{
            Startlog::go();
        }
    }

    /**
     * 设置时区 Asia/Shanghai
     * 2017年9月10日17:16:41 。没有设置这个会报错
     * @return void
     */
    private static function _setDefaultTimezone() {
        date_default_timezone_set('Asia/Shanghai');
    }
}

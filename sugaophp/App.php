<?php

namespace sugaophp;

use Whoops\Handler\PrettyPageHandler;
use Whoops\Handler\JsonResponseHandler;

class App {

    public function init() {
        $this->require_base();
        $this->whoops(); //这个必须在路由之前
//        $this->monolog();
        $this->route();
    }

    /**
     * 引入基本的文件
     */
    private function require_base() {
        require_once BASEDIR . '/Common/Common/function.php';
        require_once BASEDIR . '/sugaophp/function.php';
//        $this->config=require_once BASEDIR.'/Common/Config/config.php';这个牛逼啊。直接就返回了
    }

    /**
     * 路由
     */
    private function route() {
        $route = new Route();
        $route->parse();
    }

    /**
     * debug工具
     * http://filp.github.io/whoops/
     */
    private function whoops() {
//    public function whoops() {

        $run = new \Whoops\Run;
        $handler = new PrettyPageHandler;

// Add some custom tables with relevant info about your application,
// that could prove useful in the error page:
//        $handler->addDataTable('Killer App Details', array(
//            "Important Data" => $myApp->getImportantData(),
//            "Thingamajig-id" => $someId
//        ));

// Set the title of the error page:
//        $handler->setPageTitle("Whoops! There was a problem.");
         $handler->setPageTitle("苏羔! 这里有一个问题啊.");
        $run->pushHandler($handler);

// Add a special handler to deal with AJAX requests with an
// equally-informative JSON response. Since this handler is
// first in the stack, it will be executed before the error
// page handler, and will have a chance to decide if anything
// needs to be done.
        if (\Whoops\Util\Misc::isAjaxRequest()) {
            $run->pushHandler(new JsonResponseHandler);
        }

// Register the handler with PHP, and you're set!
        $run->register();
    }
    public function monolog(){
        $monolog=new Monolog();
        $monolog->init();
    }

}

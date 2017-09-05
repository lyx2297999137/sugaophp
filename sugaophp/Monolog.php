<?php

namespace sugaophp;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;

class Monolog {

    public function init() {
        /*         * ****1************ */
//         创建日志服务
        $logger = new Logger('my_logger');

// 添加一些处理器
        $logger->pushHandler(new StreamHandler(BASEDIR . '/View/Runtime/Monolog/my_app.log'));
        $logger->pushHandler(new FirePHPHandler());
//                $logger->error('My logger is now ready600');
//        $a=['a'=>'a','b'=>'b'];
                $logger->addWarning('aaaac');

        /*         * ****2************ */
//// 创建一些处理器
//        $stream = new StreamHandler(BASEDIR . '/View/Runtime/Monolog/my_app.log', Logger::ERROR);
//        $firephp = new FirePHPHandler();
//// 现在你就可以用日志服务了
//        // 创建应用的主要日志服务实例
//        $logger = new Logger('my_logger');
//        $logger->pushHandler($stream);
//        $logger->pushHandler($firephp);
//        $logger->addWarning('Foo');
//        $logger->addError('Bar');
//// 使用另外的通道来创建安全相关的日志服务示例
//        $securityLogger = new Logger('security');
//        $securityLogger->pushHandler($stream);
//        $securityLogger->pushHandler($firephp);
//        $securityLogger->addWarning('Foo1');
//        $securityLogger->addError('Bar1');
    }

}

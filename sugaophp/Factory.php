<?php
/**
 * 工厂模式+注册器
 */
namespace sugaophp;

use \sugaophp\Cache\File;
use sugaophp\Log\HandleLog;
class Factory {

    static function getInstance($type = '') {
        $model = Register::get($type);
        if (!$model) {
            switch ($type) {
                case 'file':
                    $model = new File();
                    break;
//                case 'config':
//                    $model =new Config();
                case 'handle_log':
                    $model=new HandleLog();
                    break;
                default :
                    throw new \RuntimeException("factory no type");
                    break;
            }
            Register::set($type, $model);
        }
        return $model;
    }

}

<?php
/**
 * 工厂模式
 */
namespace sugaophp;

use \sugaophp\Cache\File;

class Factory {

    static function getInstance($type = '') {
        $model = Register::get($type);
        if (!$model) {
            switch ($type) {
                case 'file':
                    $model = new File();
                    break;
                default :
                    throw new RuntimeException("factory no type");
                    break;
            }
            Register::set($type, $model);
        }
        return $model;
    }

}

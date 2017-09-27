<?php

namespace sugaophp\Behavior;

class appconfig {

    public function app_init(&$params){
        if (!empty($params)) {
            echo 'appconfig--app_init--' . $params . '</br>';
        } else {
            echo 'appconfig--app_init--</br>';
        }
    }
}

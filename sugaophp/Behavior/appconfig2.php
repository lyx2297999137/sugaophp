<?php

namespace sugaophp\Behavior;

class appconfig2 {

    public function app_init(&$params){
        if (!empty($params)) {
            echo 'appconfig2--app_init--' . $params . '</br>';
        } else {
            echo 'appconfig2--app_init--</br>';
        }
    }
}

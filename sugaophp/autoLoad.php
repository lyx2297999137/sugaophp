<?php
namespace sugaophp;
class autoLoad{
    static function autoload($class){
//        dump($class);
        require BASEDIR.'/'.str_replace("\\",'/',$class).'.php';
    }
}
//echo 'hello';


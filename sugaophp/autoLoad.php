<?php
namespace sugaophp;
class autoLoad{
    static function autoload($class){
//        dump($class);
        $classPath=BASEDIR.'/'.str_replace("\\",'/',$class).'.php';
        if(!is_file($classPath)){
            dump($classPath.' not found');die;
        }
        require $classPath;
    }
}
//echo 'hello';


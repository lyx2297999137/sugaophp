<?php
/**
 * log 对象接口
 * Created by PhpStorm.
 * User: Winds10
 * Date: 2017/4/10
 * Time: 22:16
 */
namespace sugaophp\Log;
interface Log{
    public function log( $fileName , $content='' , $array=[] );
}
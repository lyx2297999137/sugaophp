<?php
define('BASEDIR',__DIR__);
require_once __DIR__.'/sugaophp/autoLoad.php';
spl_autoload_register("\\sugaophp\\autoLoad::autoload");

$app=new sugaophp\App();
$app->init();
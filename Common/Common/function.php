<?php

/**
 * 浏览器友好的变量输出
 * @param mixed $var 变量
 * @param boolean $echo 是否输出 默认为True 如果为false 则返回输出字符串
 * @param string $label 标签 默认为空
 * @param boolean $strict 是否严谨 默认为true
 * @return void|string
 */
function dump1($var, $echo = true, $label = null, $strict = true) {
    $label = ($label === null) ? '' : rtrim($label) . ' ';
    if (!$strict) {
        if (ini_get('html_errors')) {
            $output = print_r($var, true);
            $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
        } else {
            $output = $label . print_r($var, true);
        }
    } else {
        ob_start();
        var_dump($var);
        $output = ob_get_clean();
        if (!extension_loaded('xdebug')) {
            $output = preg_replace('/\]\=\>\n(\s+)/m', '] => ', $output);
            $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
        }
    }
    if ($echo) {
        echo($output);
        return null;
    } else
        return $output;
}

/**
 * 循环删除指定目录下的文件及文件夹
 * @param string $dirpath 文件夹路径
 */
function WSTDelDir($dirpath) {
    $dh = opendir($dirpath);
    while (($file = readdir($dh)) !== false) {
        if ($file != "." && $file != "..") {
            $fullpath = $dirpath . "/" . $file;
            if (!is_dir($fullpath)) {
                unlink($fullpath);
            } else {
                WSTDelDir($fullpath);
                rmdir($fullpath);
            }
        }
    }
    closedir($dh);
    $isEmpty = 1;
    $dh = opendir($dirpath);
    while (($file = readdir($dh)) !== false) {
        if ($file != "." && $file != "..") {
            $isEmpty = 0;
            break;
        }
    }
    return $isEmpty;
}

/**
 * 循环建立文件夹
 * @param type $aimUrl  /View/Runtime/my_error_log
 * @return type
 */
function WSTCreateDir($aimUrl) {
    $aimUrl = str_replace('', '/', $aimUrl);
    $aimDir = '';
    $arr = explode('/', $aimUrl);
    $result = true;
    foreach ($arr as $str) {
        $aimDir .= $str . '/';
        if (!file_exists(BASEDIR . $aimDir)) {
            $result = mkdir(BASEDIR . $aimDir, 0777);
        }
    }
    return $result;
}
/**
 * 日志
 * @param type $info
 * @param type $filepath
 * @param type $filename
 * @param type $die
 */
function my_error_log($info=array(),$filepath='/temp',$filename='temp',$die=false){
    $dir_path=BASEDIR.'/View/Runtime/'.$filepath;
    if(!is_dir($dir_path)){
        WSTCreateDir('/View/Runtime/'.$filepath);
    }
    error_log(date('Y-m-d H:i:s') . "==" .var_export($info,true)."\t\n\n" . PHP_EOL, 3, BASEDIR.'/View/Runtime/'.$filepath .DIRECTORY_SEPARATOR. $filename. '.' . date('Ymd'));
    if($die){
        die;
    }
}
function lastLetter($aimUrl) {
    $lastLetter = substr($aimUrl, -1, 1);
    if ($lastLetter == "/") {
        return $aimUrl;
    } else {
        return $aimUrl . '/';
    }
}

/**
 * 建立文件
 * @param string $aimUrl
 * @param boolean $overWrite 该参数控制是否覆盖原文件
 * @return boolean
 */
function WSTCreateFile($aimUrl, $overWrite = false) {
//     $aimUrl= lastLetter($aimUrl);
    if (file_exists_case($aimUrl) && $overWrite == false) {
        return false;
    } elseif (file_exists_case($aimUrl) && $overWrite == true) {
        WSTUnlinkFile($aimUrl);
    }
    $aimDir = dirname($aimUrl);
    WSTCreateDir($aimDir);
    touch($aimUrl);
    return true;
}

/**
 * 删除文件
 * @param string $aimUrl
 * @return boolean
 */
function WSTUnlinkFile($aimUrl) {
    if (file_exists_case($aimUrl)) {
        unlink($aimUrl);
        return true;
    } else {
        return false;
    }
}

//define('IS_WIN',strstr(PHP_OS, 'WIN') ? 1 : 0 );
/**
 * 区分大小写的文件存在判断
 * @param string $filename 文件地址
 * @return boolean
 */
function file_exists_case($filename) {
//    dump($filename);
//    if (is_file($filename)) {
    if (file_exists($filename)) {
//          if (IS_WIN) {
//        if (IS_WIN && APP_DEBUG) {
//             dump(basename(realpath($filename)));
//             dump(basename($filename));
//            if (basename(realpath($filename)) != basename($filename))
//                return false;
//        }
        return true;
    }
    return false;
}

/**
  $arr=["a"=>['name'=>'n1','value'=>4],"b"=>['name'=>'n2','value'=>2],"c"=>['name'=>'n3','value'=>8],"d"=>['name'=>'n4','value'=>6]];
  uasort($arr,"uasort_func");
  $tourism_area_list = [
            'quangang' => [
                'name' => '泉港',
                'num' => 0,
                'value' => 6
            ],
            'huian' => [
                'name' => '惠安',
                'num' => 3,
                'value' => 6
            ],
            'kaifaqu' => [
                'name' => '开发区',
                'num' => 0,
                'value' => 0
            ]
        ];
            uasort($tourism_area_list, "uasort_func");
  从大到小排序
 * 有个缺点，就是全部为0的顺序也改变了
 * xiaoliao
 */ 
//    global $nc=0;这里来个全局变量？
function uasort_func($a, $b) {
    if ($a['value'] == $b['value']) {
        if ($a['num'] == $b['num']) {
            return 0;
        }
        return ($a['num'] < $b['num']) ? 1 : -1;
    }
    return ($a['value'] < $b['value']) ? 1 : -1;
}
function return666($str){
    return '666'.$str;
}
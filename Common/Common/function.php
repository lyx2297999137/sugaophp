<?php
/**
 * 浏览器友好的变量输出
 * @param mixed $var 变量
 * @param boolean $echo 是否输出 默认为True 如果为false 则返回输出字符串
 * @param string $label 标签 默认为空
 * @param boolean $strict 是否严谨 默认为true
 * @return void|string
 */
//function dump($var, $echo=true, $label=null, $strict=true) {
//    $label = ($label === null) ? '' : rtrim($label) . ' ';
//    if (!$strict) {
//        if (ini_get('html_errors')) {
//            $output = print_r($var, true);
//            $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
//        } else {
//            $output = $label . print_r($var, true);
//        }
//    } else {
//        ob_start();
//        var_dump($var);
//        $output = ob_get_clean();
//        if (!extension_loaded('xdebug')) {
//            $output = preg_replace('/\]\=\>\n(\s+)/m', '] => ', $output);
//            $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
//        }
//    }
//    if ($echo) {
//        echo($output);
//        return null;
//    }else
//        return $output;
//}
/**
 * 循环删除指定目录下的文件及文件夹
 * @param string $dirpath 文件夹路径
 */
function WSTDelDir($dirpath){
	$dh=opendir($dirpath);
	while (($file=readdir($dh))!==false) {
		if($file!="." && $file!="..") {
		    $fullpath=$dirpath."/".$file;
		    if(!is_dir($fullpath)) {
		        unlink($fullpath);
		    } else {
		        WSTDelDir($fullpath);
		        rmdir($fullpath);
		    }
	    }
	}	 
	closedir($dh);
    $isEmpty = 1;
	$dh=opendir($dirpath);
	while (($file=readdir($dh))!== false) {
		if($file!="." && $file!="..") {
			$isEmpty = 0;
			break;
		}
	}
	return $isEmpty;
}
/**
 * 建立文件夹
 * @param string $aimUrl
 * @return viod
 */
function WSTCreateDir($aimUrl) {
//    $aimUrl= lastLetter($aimUrl);
	$aimUrl = str_replace('', '/', $aimUrl);
	$aimDir = '';
	$arr = explode('/', $aimUrl);
	$result = true;
	foreach ($arr as $str) {
		$aimDir .= $str . '/';
		if (!file_exists_case($aimDir)) {
			$result = mkdir($aimDir,0777);
		}
	}
        dump($result);
	return $result;
}
function  lastLetter($aimUrl){
     $lastLetter=substr($aimUrl,-1,1);
     if($lastLetter=="/"){
         return $aimUrl;
     }else{
         return $aimUrl.'/';
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
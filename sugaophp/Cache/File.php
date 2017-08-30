<?php
namespace sugaophp\Cache;
/**
 * 文件缓存类
 */
class File {

    /**
     * 缓存连接参数
     * @var integer
     * @access protected
     */
    protected $options = array();

    /**
     * 取得变量的存储文件名
     * @access private
     * @param string $name 缓存变量名
     * @return string
     */
    private function filename($name) {
        $name = md5($name);

        // 使用子目录
        $dir = BASEDIR.'/View/Runtime/Data';
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        $filename = $dir . '/'.$name . '.php';

        return $filename;
    }

    /**
     * 写入缓存
     * @access public
     * @param string $name 缓存变量名
     * @param mixed $value  存储数据
     * @param int $expire  有效时间 0为永久
     * @return boolean
     */
    public function set($name, $value, $expire = null) {
//        N('cache_write',1);
        if (is_null($expire)) {
            $expire = 3600;
        }
        $filename = $this->filename($name);
        $data = serialize($value);
        if (function_exists('gzcompress')) {
            //数据压缩
            $data = gzcompress($data, 3);
        }
        if (true) {//开启数据校验
            $check = md5($data);
        } else {
            $check = '';
        }
        $data = "<?php\n//" . sprintf('%012d', $expire) . $check . $data . "\n?>";
        $result = file_put_contents($filename, $data);
        if ($result) {
            clearstatcache();
            return true;
        } else {
            return false;
        }
    }
    
     /**
     * 读取缓存
     * @access public
     * @param string $name 缓存变量名
     * @return mixed
     */
    public function get($name) {
        $filename   =   $this->filename($name);
        if (!is_file($filename)) {
           return false;
        }
        $content    =   file_get_contents($filename);
        if( false !== $content) {
            $expire  =  (int)substr($content,8, 12);
            if($expire != 0 && time() > filemtime($filename) + $expire) {
                //缓存过期删除缓存文件
//                echo $filename;
                $m=unlink($filename);
//                dump($m);
                return false;
            }
            if(true) {//开启数据校验
                $check  =  substr($content,20, 32);
                $content   =  substr($content,52, -3);
                if($check != md5($content)) {//校验错误
                    return false;
                }
            }else {
            	$content   =  substr($content,20, -3);
            }
            if(function_exists('gzcompress')) {
                //启用数据压缩
                $content   =   gzuncompress($content);
            }
            $content    =   unserialize($content);
            return $content;
        }
        else {
            return false;
        }
    }
    
     /**
     * 删除缓存
     * @access public
     * @param string $name 缓存变量名
     * @return boolean
     */
    public function rm($name) {
        return unlink($this->filename($name));
    }
}

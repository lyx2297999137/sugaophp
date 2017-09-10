<?php

/**
 * 模版解析
 */

namespace sugaophp;

use sugaophp\Superglobal;
use RuntimeException;
class Template {

    /** @var array $subtemplates */
    public $subtemplates = array();

    /** @var array $replacecode */
    public static $replacecode = array('search' => array(), 'replace' => array());

    /** @var array $_extract */
    private $_extract = array();

    /**
     * 读取模版
     * @return string
     */
    public function display() {/* {{{ */
        define('IN_TEMPLATE', TRUE);

        $arr_data = Superglobal::$methods;
        extract($this->_extract);

        include $this->init("{$arr_data['module']}/{$arr_data['controller']}/{$arr_data['action']}");
    }

/* }}} */

    /**
     * 模版变量赋值
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function assign($key, $value) {/* {{{ */
        $this->_extract[$key] = $value;
    }

/* }}} */

    /**
     * 模板引擎自动调用, 开发者涉及不到这个函数
     *
     * @param string $maintpl
     * @param string $subtpl 
     * @param int $timecompare 
     * @param (int|string) $templateid
     * @param string $cachefile 
     * @param string $tpldir 
     * @param string $file 
     *
     * return boolean 返回结果true代表成功
     */
    private function _checktplrefresh($maintpl, $subtpl, $timecompare, $templateid, $cachefile, $file) {/* {{{ */
        $int_subtpl = ($subtpl && file_exists($subtpl)) ? filemtime($subtpl) : 0;
//        $arr_template = Config::getConfig('template');        
        $arr_template =Superglobal::$config['template'];
        $tplrefresh = $arr_template['refresh'];
//        dump($tplrefresh);
//        dump($timecompare);
//        if (empty($timecompare) || $tplrefresh = 1 || ($tplrefresh > 1 && !(time() % $tplrefresh))) {
//            if (empty($timecompare) || $int_subtpl > $timecompare) {
                $this->_parse($maintpl, $templateid, $file, $cachefile);
                return TRUE;
//            }
//        }

        return FALSE;
    }

/* }}} */

    /**
     * 生成目录: {$path}/TemplatesCache/sugaophp.ViewTest.index.tpl.php
     *
     * @param string $file 模板文件名称
     * @param string $tpldir 指定模板路径, 如果有值, 不会读取配置文件path
     * @param int $gettplfile
     *
     * return string 模板文件内容
     */
    public function init($file, $tpldir = '', $gettplfile = 0) {/* {{{ */
        $templateid = 'sugaophp';
        $tplfile = '';
        $filemtime = 0;

//        $arr_template = Config::getConfig('template');
 $arr_template =Superglobal::$config['template'];
        if (!$tpldir) {
            $tpldir = $arr_template['path'];
        }

        $tpldir = substr($tpldir, -1) == '/' ? $tpldir : "{$tpldir}/";
        $tplfile = "{$tpldir}Templates/{$file}.{$arr_template['suffix']}";
        $cachePath="{$tpldir}TemplatesCach";
        if(!file_exists_case($cachePath)){
            WSTCreateDir($cachePath);
        }
        $cachefile = $cachePath."/{$templateid}.%s.tpl.php";
//        $cachefile = "{$tpldir}TemplatesCache/{$templateid}.%s.tpl.php";
        $cachefile = sprintf($cachefile, str_replace('/', '.', $file));
        if (!file_exists($tplfile)) {
            throw new RuntimeException("template not found ({$tplfile})");
        }

        if (file_exists($cachefile)) {
            $filemtime = filemtime($cachefile);
        }

        if ($gettplfile) {
            return $tplfile;
        }

        $this->_checktplrefresh($tplfile, $tplfile, $filemtime, $templateid, $cachefile, $file);
        return $cachefile;
    }

/* }}} */

    /**
     * 模版解析
     *
     * @param string $tpldir 模板文件地址+模版文件名
     * @param string $templateid 生成模版前缀ID
     * @param int $gettplfile
     *
     * return string 模板文件内容
     */
    private function _parse($tplfile, $templateid, $file, $cachefile) {/* {{{ */
//        dump('parse');
        $fp = @fopen($tplfile, 'rb');
        if ($fp === FALSE) {
            throw new RuntimeException("template not found ({$tplfile})");
        }

        $template = @fread($fp, filesize($tplfile));
        if ($template === FALSE) {
            throw new RuntimeException("template Can not read ({$tplfile})");
        }
        fclose($fp);

        $var_regexp = "((\\\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*(\-\>)?[a-zA-Z0-9_\x7f-\xff]*)(\[[a-zA-Z0-9_\-\.\"\'\[\]\$\x7f-\xff]+\])*)";
        $const_regexp = "([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)";

        $headerexists = preg_match("/{(sub)?template\s+[\w\/]+?header\}/", $template);
        $this->subtemplates = array();
        for ($i = 1; $i <= 3; $i++) {
            if (stripos($template, '{subtemplate') !== FALSE) {
                $template = preg_replace_callback("/([\n\r\t]*)(\<\!\-\-)?\{subtemplate\s+([a-z0-9_:\/]+)\}(\-\-\>)?([\n\r\t]*)/is", array($this, '_loadsubtemplate'), $template);
            }
        }

        $template = preg_replace_callback("/([\n\r]+)\t+/s", function($mathes) {
            return isset($mathes[1]) ? "{$mathes[1]}" : '';
        }, $template);
        $template = preg_replace_callback("/\<\!\-\-\{(.+?)\}\-\-\>/s", function($mathes) {
            return isset($mathes[1]) ? "{{$mathes[1]}}" : '';
        }, $template);
        $template = preg_replace_callback("/([\n\r\t]*)\{date\((.+?)\)\}([\n\r\t]*)/i", function($mathes) {
            return isset($mathes[1]) ? $mathes[1] . template::datetags($mathes[2]) . $mathes[3] : '';
        }, $template);
        $template = preg_replace_callback("/([\n\r\t]*)\{eval\s+(.+?)\s*\}([\n\r\t]*)/is", function($mathes) {
            return isset($mathes[1]) ? $mathes[1] . template::evaltags($mathes[2]) . $mathes[3] : '';
        }, $template);
        $template = str_replace("{LF}", "<?=\"\\n\"?>", $template);

        $template = preg_replace_callback("/\{(\\\$[a-zA-Z0-9_\-\>\[\]\'\"\$\.\x7f-\xff]+)\}/s", function($mathes) {
            return isset($mathes[1]) ? "<?={$mathes[1]}?>" : '';
        }, $template);
        $template = preg_replace_callback("/\<\?\=\<\?\=$var_regexp\?\>\?\>/s", function($mathes) {
            return isset($mathes[1]) ? template::addquote("<?={$mathes[1]}?>") : '';
        }, $template);

        $headeradd = '';
        if (!empty($this->subtemplates)) {
            $headeradd .= "\n0\n";
            foreach ($this->subtemplates as $fname) {
                $headeradd .= "|| \$this->_checktplrefresh('$tplfile', '$fname', " . time() . ", '$templateid', '$cachefile', '$file')\n";
            }
            $headeradd .= ';';
        }

        $template = "<? if(!defined('IN_TEMPLATE')) exit('Access Denied'); {$headeradd}?>\n$template";

        $template = preg_replace_callback("/([\n\r\t]*)\{template\s+([a-z0-9_:\/]+)\}([\n\r\t]*)/is", function($mathes) {
//            dump($mathes);
            return isset($mathes[1]) ? $mathes[1] . template::stripvtags("<? include \$this->init('{$mathes[2]}'); ?>") . $mathes[3] : '';
        }, $template);
        $template = preg_replace_callback("/([\n\r\t]*)\{template\s+(.+?)\}([\n\r\t]*)/is", function($mathes) {
//            dump($mathes);
            return isset($mathes[1]) ? $mathes[1] . template::stripvtags("<? include \$this->init('{$mathes[2]}'); ?>") . $mathes[3] : '';
        }, $template);
        $template = preg_replace_callback("/([\n\r\t]*)\{echo\s+(.+?)\}([\n\r\t]*)/is", function($mathes) {
            return $mathes[1] . template::stripvtags("<? echo {$mathes[2]}; ?>") . $mathes[3];
        }, $template);
        $template = preg_replace_callback("/([\n\r\t]*)\{if\s+(.+?)\}([\n\r\t]*)/is", function($mathes) {
            return template::stripvtags("{$mathes[1]}<? if({$mathes[2]}) { ?>{$mathes[3]}");
        }, $template);

        $template = preg_replace_callback("/([\n\r\t]*)\{elseif\s+(.+?)\}([\n\r\t]*)/is", function($mathes) {
            return template::stripvtags("{$mathes[1]}<? } elseif({$mathes[2]}) { ?>{$mathes[3]}");
        }, $template);
        $template = preg_replace_callback("/\{else\}/i", function($mathes) {
            return '<? } else { ?>';
        }, $template);
        $template = preg_replace_callback("/\{\/if\}/i", function($mathes) {
            return '<? } ?>';
        }, $template);
        $template = preg_replace_callback("/([\n\r\t]*)\{loop\s+(\S+)\s+(\S+)\}[\n\r\t]*/is", function($mathes) {
            return template::stripvtags("{$mathes[1]}<? if(is_array({$mathes[2]})) foreach({$mathes[2]} as {$mathes[3]}) { ?>");
        }, $template);
        $template = preg_replace_callback("/([\n\r\t]*)\{loop\s+(\S+)\s+(\S+)\s+(\S+)\}[\n\r\t]*/is", function($mathes) {
            return template::stripvtags("{$mathes[1]}<? if(is_array({$mathes[2]})) foreach({$mathes[2]} as {$mathes[3]} => {$mathes[4]}) { ?>");
        }, $template);
        $template = preg_replace_callback("/\{\/loop\}/i", function($mathes) {
            return '<? } ?>';
        }, $template);
        $template = preg_replace_callback("/\{$const_regexp\}/s", function($mathes) {
            return '<?=\\1?>';
        }, $template);
        if (!empty(self::$replacecode)) {
            $template = str_replace(self::$replacecode['search'], self::$replacecode['replace'], $template);
        }

        $template = preg_replace_callback("/ \?\>[\n\r]*\<\? /s", function($mathes) {
            return ' ';
        }, $template);

        if (!@$fp = fopen($cachefile, 'w')) {
            throw new RuntimeException("template cache file write failed ({$cachefile})");
        }

        $template = preg_replace_callback("/\"(http)?[\w\.\/:]+\?[^\"]+?&[^\"]+?\"/", function($mathes) {
            return template::transamp("{$mathes[0]}");
        }, $template);
        $template = preg_replace_callback("/\<script[^\>]*?src=\"(.+?)\"(.*?)\>\s*\<\/script\>/is", function($mathes) {
            return template::stripscriptamp("{$mathes[1]}", "{$mathes[2]}");
        }, $template);
        
        $template = preg_replace_callback("/\<script\>(.*)?\<\/script\>/is", function($mathes) {
            return template::stripscriptamp1("{$mathes[1]}");
        }, $template);
       
        
        $template = preg_replace_callback("/([\n\r\t]*)\{block\s+([a-zA-Z0-9_\[\]]+)\}(.+?)\{\/block\}/is", function($mathes) {
            return $mathes[1] . template::stripblock("{$mathes[2]}", "{$mathes[3]}");
        }, $template);
        $template = preg_replace_callback("/\<\?(\s{1})/is", function($mathes) {
            return "<?php{$mathes[1]}";
        }, $template);
        $template = preg_replace_callback("/\<\?\=(.+?)\?\>/is", function($mathes) {
            return "<?php echo {$mathes[1]};?>";
        }, $template);

        flock($fp, 2);
        fwrite($fp, $template);
        fclose($fp);
    }

/* }}} */

    /**
     * datetags
     * @param string $parameter
     * @return string
     */
    public static function datetags($parameter) {/* {{{ */
        $parameter = stripslashes($parameter);
        $i = count(self::$replacecode['search']);
        self::$replacecode['search'][$i] = $search = "<!--DATE_TAG_$i-->";
        self::$replacecode['replace'][$i] = "<?php echo date('{$parameter}');?>";
        return $search;
    }

/* }}} */

    /**
     * evaltags
     * @param string $php
     * @return string
     */
    public static function evaltags($php) {/* {{{ */
        $php = str_replace('\"', '"', $php);
        $i = count(self::$replacecode['search']);
        self::$replacecode['search'][$i] = $search = "<!--EVAL_TAG_$i-->";
        self::$replacecode['replace'][$i] = "<?php $php?>";
        return $search;
    }

/* }}} */

    /**
     * _loadsubtemplate
     * @param string $php
     * @return string
     */
    private function _loadsubtemplate($matches) {/* {{{ */
//        dump('_loadsubtemplate');die;
        $tplfile = $this->init($matches[3], '', 1);
        if ($content = @implode('', file($tplfile))) {
            $this->subtemplates[] = $tplfile;
            return $matches[1] . $content . $matches[5];
        } else {
            return $matches[1] . '<!-- ' . $file . ' -->' . $matches[5];
        }
    }

/* }}} */

    /**
     * transamp
     * @param string $str
     * @return string
     */
    public static function transamp($str) {/* {{{ */
        $str = str_replace('&', '&amp;', $str);
        $str = str_replace('&amp;amp;', '&amp;', $str);
        $str = str_replace('\"', '"', $str);
        return $str;
    }

/* }}} */

    /**
     * addquote
     * @param string $var
     * @return string
     */
    public static function addquote($var) {/* {{{ */
        return str_replace("\\\"", "\"", preg_replace_callback("/\[([a-zA-Z0-9_\-\.\x7f-\xff]+)\]/s", function($mathes) {
                    return "['{$mathes[1]}']";
                }, $var));
    }

/* }}} */

    /**
     * stripvtags
     * @param string $expr
     * @param string $statement
     * @return string
     */
    public static function stripvtags($expr, $statement = '') {/* {{{ */
        $expr = str_replace("\\\"", "\"", preg_replace_callback("/([\n\r\t]*)\<\?\=(\\\$.+?)\?\>([\n\r\t]*)/s", function($mathes) {
                    return $mathes[2];
                }, $expr));
        $statement = str_replace("\\\"", "\"", $statement);
        return $expr . $statement;
    }

/* }}} */

    /**
     * stripscriptamp
     * @param string $s
     * @param string $extra
     * @return string
     */
    public static function stripscriptamp($s, $extra) {/* {{{ */
        $extra = str_replace('\\"', '"', $extra);
        $s = str_replace('&amp;', '&', $s);
        return "<script src=\"$s\" type=\"text/javascript\"$extra></script>";
    }

     public static function stripscriptamp1($s) {/* {{{ */
        $s = str_replace('&amp;', '&', $s);
        return "<script>$s</script>";
    }
/* }}} */

    /**
     * stripblock
     * @param string $var
     * @param string $s
     * @return string
     */
    public static function stripblock($var, $s) {/* {{{ */
        $s = str_replace('\\"', '"', $s);
        $s = preg_replace_callback("/<\?=\\\$(.+?)\?>/", function($mathes) {
            return "{\${$mathes[1]}}";
        }, $s);
        preg_match_all("/<\?=(.+?)\?>/e", $s, $constary);
        $constadd = '';
        $constary[1] = array_unique($constary[1]);
        foreach ($constary[1] as $const) {
            $constadd .= '$__' . $const . ' = ' . $const . ';';
        }
        $s = preg_replace_callback("/<\?=(.+?)\?>/", function($mathes) {
            return "{\$__{$mathes[1]}}";
        }, $s);
        $s = str_replace('?>', "\n\$$var .= <<<EOF\n", $s);
        $s = str_replace('<?', "\nEOF;\n", $s);

        $phpeol = PHP_EOL;
        return "<?{$phpeol}{$constadd}\${$var} = <<<EOF{$phpeol}" . $s . "{$phpeol}EOF;{$phpeol}?>";
    }

/* }}} */
}

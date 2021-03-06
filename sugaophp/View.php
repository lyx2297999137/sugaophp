<?php

// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

namespace sugaophp;

/**
 * ThinkPHP 视图类
 */
class View {

    private $config = array(
        'TMPL_ENGINE_TYPE' => 'Think', // 默认模板引擎 以下设置仅对使用Think模板引擎有效
        'TMPL_TEMPLATE_SUFFIX' => '.html',
        'DEFAULT_THEME' => '',
        'TMPL_DETECT_THEME' => false, // 自动侦测模板主题
        'VAR_TEMPLATE' => 't', // 默认模板切换变量
         'DEFAULT_V_LAYER'       =>  'View', // 默认的视图层名称
        
    );

    /**
     * 模板输出变量
     * @var tVar
     * @access protected
     */
    protected $tVar = array();

    /**
     * 模板主题
     * @var theme
     * @access protected
     */
    protected $theme = '';

    public function __construct() {
        define('THEME_PATH', $this->getThemePath());
    }

    /**
     * 模板变量赋值
     * @access public
     * @param mixed $name
     * @param mixed $value
     */
    public function assign($name, $value = '') {
        if (is_array($name)) {
            $this->tVar = array_merge($this->tVar, $name);
        } else {
            $this->tVar[$name] = $value;
        }
    }

    /**
     * 取得模板变量的值
     * @access public
     * @param string $name
     * @return mixed
     */
    public function get($name = '') {
        if ('' === $name) {
            return $this->tVar;
        }
        return isset($this->tVar[$name]) ? $this->tVar[$name] : false;
    }

    /**
     * 加载模板和页面输出 可以返回输出内容
     * @access public
     * @param string $templateFile 模板文件名
     * @param string $charset 模板输出字符集
     * @param string $contentType 输出类型
     * @param string $content 模板输出内容
     * @param string $prefix 模板缓存前缀
     * @return mixed
     */
    public function display($templateFile = '', $charset = '', $contentType = '', $content = '', $prefix = '') {
        // 视图开始标签
        Hook::listen('view_begin', $templateFile);
        // 解析并获取模板内容
        $content = $this->fetch($templateFile, $content, $prefix);
        // 输出模板内容
        $this->render($content, $charset, $contentType);
        // 视图结束标签
        Hook::listen('view_end');
    }

    /**
     * 输出内容文本可以包括Html
     * @access private
     * @param string $content 输出内容
     * @param string $charset 模板输出字符集
     * @param string $contentType 输出类型
     * @return mixed
     */
    private function render($content, $charset = '', $contentType = '') {
        if (empty($charset))
            $charset = 'utf-8';
        if (empty($contentType))
            $contentType = 'text/html';
        // 网页字符编码
        header('Content-Type:' . $contentType . '; charset=' . $charset);
        header('Cache-control:private');  // 页面缓存控制
        header('X-Powered-By:ThinkPHP');
        // 输出模板文件
        echo $content;
    }

    /**
     * 解析和获取模板内容 用于输出
     * @access public
     * @param string $templateFile 模板文件名
     * @param string $content 模板输出内容
     * @param string $prefix 模板缓存前缀
     * @return string
     */
    public function fetch($templateFile = '', $content = '', $prefix = '') {
        if (empty($content)) {
            $templateFile = $this->parseTemplate($templateFile);
            // 模板文件不存在直接返回
            if (!is_file($templateFile))
                E('_TEMPLATE_NOT_EXIST_' . $templateFile);
        }else {
            defined('THEME_PATH') or define('THEME_PATH', $this->getThemePath());
        }
        // 页面缓存
        ob_start();
        ob_implicit_flush(0);
        if ('php' == strtolower($this->config['TMPL_ENGINE_TYPE'])) { // 使用PHP原生模板
            $_content = $content;
            // 模板阵列变量分解成为独立变量
            extract($this->tVar, EXTR_OVERWRITE);
            // 直接载入PHP模板
            empty($_content) ? include $templateFile : eval('?>' . $_content);
        } else {
            // 视图解析标签
            $params = array('var' => $this->tVar, 'file' => $templateFile, 'content' => $content, 'prefix' => $prefix);
            Hook::listen('view_parse', $params);
        }
        // 获取并清空缓存
        $content = ob_get_clean();
        // 内容过滤标签
        Hook::listen('view_filter', $content);
        // 输出模板文件
        return $content;
    }

    /**
     * 自动定位模板文件
     * @access protected
     * @param string $template 模板文件规则
     * @return string
     */
    public function parseTemplate($template = '') {
        if (is_file($template)) {
            return $template;
        }
        $depr = '/';
        $template = str_replace(':', $depr, $template);

        // 获取当前模块
        $module = MODULE_NAME;
        if (strpos($template, '@')) { // 跨模块调用模版文件
            list($module, $template) = explode('@', $template);
        }
        // 获取当前主题的模版路径
        defined('THEME_PATH') or define('THEME_PATH', $this->getThemePath($module));

        // 分析模板文件规则
        if ('' == $template) {
            // 如果模板文件名为空 按照默认规则定位
            $template = CONTROLLER_NAME . $depr . ACTION_NAME;
        } elseif (false === strpos($template, $depr)) {
            $template = CONTROLLER_NAME . $depr . $template;
        }
        $file = THEME_PATH . $template . $this->config['TMPL_TEMPLATE_SUFFIX'];
        return $file;
    }

    /**
     * 获取当前的模板路径
     * @access protected
     * @param  string $module 模块名
     * @return string
     */
    protected function getThemePath($module = MODULE_NAME) {
        // 获取当前主题名称
        $theme = $this->getTemplateTheme();
        // 获取当前主题的模版路径
        //$tmplPath = C('VIEW_PATH'); // 模块设置独立的视图目录
        //if (!$tmplPath) {
            // 定义TMPL_PATH 则改变全局的视图目录到模块之外
            $tmplPath = defined('TMPL_PATH') ? TMPL_PATH . $module . '/' : APP_PATH . $module . '/' . $this->config['DEFAULT_V_LAYER'] . '/';
       // }
        return $tmplPath . $theme;
    }

    /**
     * 设置当前输出的模板主题
     * @access public
     * @param  mixed $theme 主题名称
     * @return View
     */
    public function theme($theme) {
        $this->theme = $theme;
        return $this;
    }

    /**
     * 获取当前的模板主题
     * @access private
     * @return string
     */
    private function getTemplateTheme() {
        if ($this->theme) { // 指定模板主题
            $theme = $this->theme;
        } else {
            /* 获取模板主题名称 */
            $theme = $this->config['DEFAULT_THEME'];
            if ($this->config['TMPL_DETECT_THEME']) {// 自动侦测模板主题
                $t = $this->config['VAR_TEMPLATE'];
                if (isset($_GET[$t])) {
                    $theme = $_GET[$t];
                } elseif (cookie('think_template')) {
                    $theme = cookie('think_template');
                }
                if (!in_array($theme, explode(',', C('THEME_LIST')))) {
                    $theme = $this->config['DEFAULT_THEME'];
                }
                cookie('think_template', $theme, 864000);
            }
        }
        defined('THEME_NAME') || define('THEME_NAME', $theme);                  // 当前模板主题名称
        return $theme ? $theme . '/' : '';
    }

}

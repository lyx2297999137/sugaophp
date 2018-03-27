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

namespace sugaophp\Behavior;

use sugaophp\Cache\Storage;

/**
 * 系统行为扩展：模板解析
 */
class ParseTemplateBehavior {

    private $config = array(//sugao
        'CACHE_PATH' => BASEDIR . '/View/Runtime/App/' . MODULE_NAME . '/',
           'TMPL_TEMPLATE_SUFFIX' => '.html', // 默认模板文件后缀
        'TMPL_CACHFILE_SUFFIX' => '.php', // 默认模板缓存后缀
        'TMPL_CACHE_ON' => true, // 是否开启模板编译缓存,设为false则每次都会重新编译
        'TMPL_CACHE_TIME' => 0, // 模板缓存有效期 0 为永久，(以数字为值，单位:秒)
        'TMPL_ENGINE_TYPE' => 'Think', // 默认模板引擎 以下设置仅对使用Think模板引擎有效
        'TMPL_CACHE_PREFIX' => '', // 模板缓存前缀标识，可以动态改变
          'LAYOUT_ON' => false, // 是否启用布局
        'LAYOUT_NAME' => 'layout', // 当前布局名称 默认为layout
    );

    // 行为扩展的执行入口必须是run
    public function run(&$_data) {
        $engine = strtolower($this->config['TMPL_ENGINE_TYPE']);
        $_content = empty($_data['content']) ? $_data['file'] : $_data['content'];
        $_data['prefix'] = !empty($_data['prefix']) ? $_data['prefix'] : $this->config['TMPL_CACHE_PREFIX'];
        if ('think' == $engine) { // 采用Think模板引擎
            if ((!empty($_data['content']) && $this->checkContentCache($_data['content'], $_data['prefix'])) || $this->checkCache($_data['file'], $_data['prefix'])) { // 缓存有效
                //载入模版缓存文件
                Storage::load($this->config['CACHE_PATH'] . $_data['prefix'] . md5($_content) . $this->config['TMPL_CACHFILE_SUFFIX'], $_data['var']);
            } else {
                $tpl = new \sugaophp\Template\Template();
                // 编译并加载模板文件
                $tpl->fetch($_content, $_data['var'], $_data['prefix']);
            }
        } else {
            // 调用第三方模板引擎解析和输出
            if (strpos($engine, '\\')) {
                $class = $engine;
            } else {
                $class = 'Think\\Template\\Driver\\' . ucwords($engine);
            }
            if (class_exists($class)) {
                $tpl = new $class;
                $tpl->fetch($_content, $_data['var']);
            } else {  // 类没有定义
                E(L('_NOT_SUPPORT_') . ': ' . $class);
            }
        }
    }

    /**
     * 检查缓存文件是否有效
     * 如果无效则需要重新编译
     * @access public
     * @param string $tmplTemplateFile  模板文件名
     * @return boolean
     */
    protected function checkCache($tmplTemplateFile, $prefix = '') {
        if (!$this->config['TMPL_CACHE_ON']) // 优先对配置设定检测
            return false;
        $tmplCacheFile = $this->config['CACHE_PATH'] . $prefix . md5($tmplTemplateFile) . $this->config['TMPL_CACHFILE_SUFFIX'];
        if (!Storage::has($tmplCacheFile)) {
            return false;
        } elseif (filemtime($tmplTemplateFile) > Storage::get($tmplCacheFile, 'mtime')) {
            // 模板文件如果有更新则缓存需要更新
            return false;
        } elseif ($this->config['TMPL_CACHE_TIME'] != 0 && time() > Storage::get($tmplCacheFile, 'mtime') + $this->config['TMPL_CACHE_TIME']) {
            // 缓存是否在有效期
            return false;
        }
        // 开启布局模板
        if ($this->config['LAYOUT_ON']) {
            $layoutFile = THEME_PATH . $this->config['LAYOUT_NAME'] . $this->config['TMPL_TEMPLATE_SUFFIX'];
            if (filemtime($layoutFile) > Storage::get($tmplCacheFile, 'mtime')) {
                return false;
            }
        }
        // 缓存有效
        return true;
    }

    /**
     * 检查缓存内容是否有效
     * 如果无效则需要重新编译
     * @access public
     * @param string $tmplContent  模板内容
     * @return boolean
     */
    protected function checkContentCache($tmplContent, $prefix = '') {
        if (Storage::has($this->config['CACHE_PATH'] . $prefix . md5($tmplContent) . $this->config['TMPL_CACHFILE_SUFFIX'])) {
            return true;
        } else {
            return false;
        }
    }

}

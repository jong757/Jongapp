<?php
/**
 *  Language.class.php PHPCMS应用程序创建类
 *
 * @copyright			(C) 2005-2010 PHPCMS
 * @license				http://www.phpcms.cn/license/
 * @lastmodify			2010-6-7
 * 使用示例 
 * echo Language::load('welcome_message', ['username' => 'John'], 'member,guestbook');
 * json示例
 * { "welcome_message": "欢迎, {username}!"}
 */

class Language {
    private static $LANG = [];
    private static $LANG_MODULES = [];
    private static $lang = '';

    /**
     * 加载语言文件
     *
     * @param string $language 标示符
     * @param array $pars 转义的数组, 二维数组 ,'key1'=>'value1','key2'=>'value2',
     * @param string $modules 多个模块之间用半角逗号隔开，如：member,guestbook
     * @return string 语言字符
     */
    public static function load($language = 'no_language', $pars = [], $modules = '') {
        self::initialize();

        // 加载模块语言文件
        if ($modules) {
            foreach (explode(',', $modules) as $m) {
                self::loadModuleLanguage($m);
            }
        }

        // 返回语言字符
        return self::getLanguageString($language, $pars);
    }

    /**
     * 初始化语言设置
     */
    private static function initialize() {
        if (self::$LANG) return;

        self::$lang = defined('IN_ADMIN') ? (SYS_STYLE ?: YSY_LANG) : YSY_LANG;
        defined('ROUTE_M') or define('ROUTE_M', 'content');

        self::loadSystemLanguage();
    }

    /**
     * 加载系统语言文件
     */
    private static function loadSystemLanguage() {
        $filename = LANG_PATH . self::$lang . DS . 'system.json';
		
        if (file_exists($filename)) {
            self::$LANG = json_decode(file_get_contents($filename), true);
        }
        if (defined('IN_ADMIN')) {
            $filename = LANG_PATH . self::$lang . DS . 'system_menu.json';
            if (file_exists($filename)) {
                self::$LANG = array_merge(self::$LANG, json_decode(file_get_contents($filename), true));
            }
        }
        $filename = PATH . ROUTE_M . DS . 'language' . DS . self::$lang . '.json';
		
        if (file_exists($filename)) {
            self::$LANG = array_merge(self::$LANG, json_decode(file_get_contents($filename), true));
        }
    }

    /**
     * 加载模块语言文件
     *
     * @param string $module 模块名
     */
    private static function loadModuleLanguage($module) {
        $filename = PATH . 'languages' . DS . self::$lang . DS . $module . '.json';
        if (file_exists($filename) && !isset(self::$LANG_MODULES[$module])) {
            self::$LANG = array_merge(self::$LANG, json_decode(file_get_contents($filename), true));
            self::$LANG_MODULES[$module] = true;
        }
    }

    /**
     * 获取语言字符串
     *
     * @param string $language 标示符
     * @param array $pars 转义的数组
     * @return string 语言字符
     */
    private static function getLanguageString($language, $pars) {
        if (!isset(self::$LANG[$language])) {
            return $language;
        }
        $languageString = self::$LANG[$language];
        foreach ($pars as $_k => $_v) {
            $languageString = str_replace('{' . $_k . '}', $_v, $languageString);
        }
        return $languageString;
    }
}

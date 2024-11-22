<?php
 /**
 *  constant.der.php 常量库 
 *
 * @copyright			(C) 2024-2025 Jong 
 * @license				qq:3865176
 * @lastmodify			2024-11-14 
 */
define('IN_APP', true);
//缓存文件夹
define('CACHE_PATH',PATHS.'storage'.DS.'cache'.DS);
//配置文件夹
define('CONFIG_PATH',PATHS.'storage'.DS.'config'.DS);
//错误日志文件夹
define('LOGS_PATH',PATHS.'storage'.DS.'logs'.DS);
//语言文件夹
define('LANG_PATH',PATHS.'resources'.DS.'lang'.DS);
//错误页面文件夹
define('ERROR_PATH',PATHS.'resources'.DS.'views'.DS);

//主机协议
define('SITE_PROTOCOL', isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://');
//当前访问的主机名
define('SITE_URL', (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : ''));
//来源
define('HTTP_REFERER', isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '');


//系统开始时间
define('SYS_START_TIME', microtime());
//加载错误日志
sys::loadEnv(CONFIG_PATH,'system','errorlog') ? set_error_handler('my_error_handler') : error_reporting(E_ERROR | E_WARNING | E_PARSE);

//设置本地时差
function_exists('date_default_timezone_set') && date_default_timezone_set(sys::loadEnv(CONFIG_PATH,'system','timezone'));

//输出页面字符集
define('CHARSET' ,	sys::loadEnv(CONFIG_PATH,'system','charset'));
header('Content-type: text/html; charset='.CHARSET);

define('SYS_TIME', time());
//定义网站根路径
define('WEB_PATH',	sys::loadEnv(CONFIG_PATH,'system','web_path'));
//动态程序路径
define('APP_PATH',	sys::loadEnv(CONFIG_PATH,'system','app_path'));
//默认程序入口
define('DEFAULTS',	sys::loadEnv(CONFIG_PATH,'system','defaults'));
//默认API入口
define('API_PATH',	sys::loadEnv(CONFIG_PATH,'system','api'));
//后台静态目录
define('SATAICS_PATH',	sys::loadEnv(CONFIG_PATH,'system','statics_path'));

//语言
define('YSY_LANG', 	sys::loadEnv(CONFIG_PATH,'system','lang'));

//后台路径
define('ADMIN_LOGIN_PATH',	sys::loadEnv(CONFIG_PATH,'system','admin_login_path'));

//应用静态文件路径
define('PLUGIN_STATICS_PATH',WEB_PATH.'statics/plugin/');

if(	sys::loadEnv(CONFIG_PATH,'system','gzip') && function_exists('ob_gzhandler')) {
	ob_start('ob_gzhandler');
} else {
	ob_start();
}

/**
 * 输出自定义错误Debug
 *
 * @param $errno 错误号
 * @param $errstr 错误描述
 * @param $errfile 报错文件地址
 * @param $errline 错误行号
 * @return string 错误提示
 */
function my_error_handler($errno, $errstr, $errfile, $errline) {
	if($errno==8) return '';
	$errfile = str_replace(PATH,'',$errfile);
	
	if(defined('APP_DEBUG')&&APP_DEBUG){ 
		debug::addmsg($errno.' | '.str_pad($errstr,30).' | '.$errfile.' | '.$errline,2); 
	}
	
	if(sys::loadEnv(LOGS_PATH,'system','errorlog')) {
		$Excep = sys::loadSysClass('ExceptionHandler');
		$Excep->setGlobalHandler(LOGS_PATH.'error_log.php', $errstr, 'ERROR', [
			'errno' => $errno, 
			'errstr' => $errstr,
			'errfile' => $errfile,
			'errline' => $errline,
			]
		);
		// $Excep->handleException();
		/* error_log('<?php exit;?>'.date('m-d H:i:s',time()).' | '.$errno.' | '.str_pad($errstr,30).' | '.$errfile.' | '.$errline."\r\n", 3, LOGS_PATH.'error_log.php'); */
	} 
	
}
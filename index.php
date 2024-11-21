<?php
/**
*  index.php Jong 
* 
* @copyright			(C) 2024-2025 Jong 
* @license				QQ:3865176
* @lastmodify			2024-11-14 
*/
// print_r($_SERVER);
//目录分隔符
define('DS', DIRECTORY_SEPARATOR);
 //程序根目录
define('PATHS', dirname(__FILE__).DS);

//debug
define('APP_DEBUG', true);//开启调试true false
	include PATHS.'storage'.DS.'debug'.DS.'debug.class.php';

//加载核心
include PATHS.DS.'app'.DS.'sys.php';
sys::app();

// 判断debug ajax 请求的处理方式就不输出调试了 免得js格式错误 
if(defined('APP_DEBUG')&&APP_DEBUG){
	if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"])=="xmlhttprequest"){
		}else{
			debug::message(); 
		}
}


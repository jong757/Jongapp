<?php
/**
*  Application.class.php Jong 
* 
* @copyright			(C) 2024-2025 Jong 
* @license				qq:3865176
* @lastmodify			2024-11-19 
*/
class Application {
	
	/**
	 * 构造函数
	 */
	public function __construct() {
		$param = sys::loadSysClass('Param');
		define('ROUTE_M', $param->route_m());
		define('ROUTE_C', $param->route_c());
		define('ROUTE_A', $param->route_a());
		$this->init();
	}
	
	/**
	 * 初始化
	 */
	private function init() {
		$controller = $this->loadController();
		if (!method_exists($controller, ROUTE_A) || preg_match('/^[_]/i', ROUTE_A)) {
			exit('Action does not exist or is protected.');
		}
		call_user_func([$controller, ROUTE_A]);
	}
	
	/**
	 * 加载控制器
	 * @param string $filename
	 * @param string $module
	 * @return object
	 */
	private function loadController($filename = '', $module = '') {
		$filename = $filename ?: ROUTE_C;
		$module = $module ?: ROUTE_M;
		$filepath = PATH . 'module' . DS . $module . DS . $filename . '.php';

		if (!file_exists($filepath)) {
			exit('Controller does not exist.');
		}

		include $filepath;
		$classname = $filename;
		if (!class_exists($classname)) {
			exit('Controller does not exist.');
		}
		
		return new $classname;
	}
}

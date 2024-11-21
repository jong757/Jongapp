<?php
/**
 *  param.class.php 参数处理类
 *
 * @copyright			(C) 2024-2025 Jong 
 * @license				qq:3865176
 * @lastmodify			2024-11-14 
 */
class Param {

		// 路由配置
		public $route_config = '';

		public function __construct() {
			$_POST = new_addslashes($_POST);
			$_GET = new_addslashes($_GET);
			$_REQUEST = new_addslashes($_REQUEST);
			$_COOKIE = new_addslashes($_COOKIE);

			$this->route_config = sys::loadEnv(CONFIG_PATH,'route', SITE_URL) ?: sys::loadEnv(CONFIG_PATH,'route', 'app');
			
			$this->initializeGlobals('POST');
			$this->initializeGlobals('GET');

			if (isset($_GET['page'])) {
				$_GET['page'] = min(max(intval($_GET['page']), 1), 1000000000);
			}
		}

		/**
		 * 初始化请求全局变量
		 * @param string $method 请求方法
		 */
		private function initializeGlobals($method) {
			foreach ($this->route_config['data'][$method] ?? [] as $key => $value) {
				$GLOBALS["_$method"][$key] = $GLOBALS["_$method"][$key] ?? $value;
			}
		}
		
		/**
		 * 路由获取
		 */
		public function getRoute($index) {
			$keys = array_keys($this->route_config);
			
			return isset($keys[$index]) ? $keys[$index] : null;
		}

		/**
		 * 获取模型
		 */
		public function route_m() {
			return $this->getRouteValue($this->getRoute(0), $this->route_config[$this->getRoute(0)]);
		}

		/**
		 * 获取控制器
		 */
		public function route_c() {
			return $this->getRouteValue($this->getRoute(1), $this->route_config[$this->getRoute(1)]);
		}

		/**
		 * 获取事件
		 */
		public function route_a() {
			return $this->getRouteValue($this->getRoute(2), $this->route_config[$this->getRoute(2)]);
		}
		
		/**
		 * 获取路由值
		 * @param string $type 类型 ('m', 'c', 'a')
		 * @param string $default 默认值
		 * @return string
		 */
		private function getRouteValue($type, $default) {
			$value = $this->safe_deal($_GET[$type] ?? $_POST[$type] ?? '');
			return $value ?: $default;
		}

		/**
		 * 安全处理函数
		 * 处理m,a,c
		 */
		private function safe_deal($str) {
			return str_replace(['/', '.'], '', $str);
		}

		// /**
		//  * 设置 cookie
		//  * @param string $var 变量名
		//  * @param string $value 变量值
		//  * @param int $time 过期时间
		//  */
		// public static function set_cookie($var, $value = '', $time = 0) {
		// 	$time = $time > 0 ? $time : ($value == '' ? SYS_TIME - 3600 : 0);
		// 	$s = $_SERVER['SERVER_PORT'] == '443';
		// 	$httponly = in_array($var, ['userid', 'auth']);
		// 	$var = sys::loadEnv(CONFIG_PATH,'system', 'cookie_pre') . $var;
		// 	$_COOKIE[$var] = $value;

		// 	if (is_array($value)) {
		// 		foreach ($value as $k => $v) {
		// 			setcookie($var . "[$k]", sys_auth($v, 'ENCODE', md5(PATH . 'cookie' . $var) . sys::loadEnv(CONFIG_PATH,'system', 'auth_key')), $time, sys::loadEnv(CONFIG_PATH,'system', 'cookie_path'), sys::loadEnv(CONFIG_PATH,'system', 'cookie_domain'), $s, $httponly);
		// 		}
		// 	} else {
		// 		setcookie($var, sys_auth($value, 'ENCODE', md5(PATH . 'cookie' . $var) . sys::loadEnv(CONFIG_PATH,'system', 'auth_key')), $time, sys::loadEnv(CONFIG_PATH,'system', 'cookie_path'), sys::loadEnv(CONFIG_PATH,'system', 'cookie_domain'), $s, $httponly);
		// 	}
		// }

		// /**
		//  * 获取通过 set_cookie 设置的 cookie 变量
		//  * @param string $var 变量名
		//  * @param string $default 默认值
		//  * @return mixed 成功则返回cookie 值，否则返回 false
		//  */
		// public static function get_cookie($var, $default = '') {
		// 	$var_base = $var;
		// 	$var = sys::loadEnv(CONFIG_PATH,'system', 'cookie_pre') . $var;
		// 	$value = isset($_COOKIE[$var]) ? sys_auth($_COOKIE[$var], 'DECODE', md5(	PATH . 'cookie' . $var) . sys::loadEnv(CONFIG_PATH,'system', 'auth_key')) : $default;

		// 	if (in_array($var_base, ['_userid', 'userid', 'siteid', '_groupid', '_roleid'])) {
		// 		$value = intval($value);
		// 	} elseif (in_array($var_base, ['_username', 'username', '_nickname', 'admin_username', 'sys_lang'])) {
		// 		$value = safe_replace($value);
		// 	}
		// 	return $value;
		// }
}
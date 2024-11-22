<?php
/**
 *  global.func.php 公共函数库
 *
 * @copyright			(C) 2024-2025 Jong 
 * @license				qq:3865176
 * @lastmodify			2024-11-14 
 */

/**
 * 返回经addslashes处理过的字符串或数组
 * @param $string 需要处理的字符串或数组
 * @return mixed
 */
function new_addslashes($string){
	if(!is_array($string)) return addslashes($string);
	foreach($string as $key => $val) $string[$key] = new_addslashes($val);
	return $string;
}

/**
 * 返回经stripslashes处理过的字符串或数组
 * @param $string 需要处理的字符串或数组
 * @return mixed
 */
function new_stripslashes($string) {
	if(!is_array($string)) return stripslashes($string);
	foreach($string as $key => $val) $string[$key] = new_stripslashes($val);
	return $string;
}

/**
 * xss过滤函数
 *
 * @param $string
 * @return string
 */
function remove_xss($string, $options = array(), $spec = '') {
    if (!is_array($string)) {
        if (!function_exists('htmLawed')) {
            sys::loadFunc('htmLawed');
        }
        return htmLawed($string, array_merge(array('safe' => 1, 'balanced' => 0), $options), $spec);
    }
    foreach ($string as $k => $v) {
        $string[$k] = remove_xss($v, $options, $spec);
    }
    return $string;
}

/**
 * 安全过滤函数
 *
 * @param $string
 * @return string
 */
function safe_replace($string) {
	$string = str_replace('%20','',$string);
	$string = str_replace('%27','',$string);
	$string = str_replace('%2527','',$string);
	$string = str_replace('*','',$string);
	$string = str_replace('"','&quot;',$string);
	$string = str_replace("'",'',$string);
	$string = str_replace('"','',$string);
	$string = str_replace(';','',$string);
	$string = str_replace('<','&lt;',$string);
	$string = str_replace('>','&gt;',$string);
	$string = str_replace("{",'',$string);
	$string = str_replace('}','',$string);
	$string = str_replace('\\','',$string);
	return $string;
}


/**
 * GetCustom URL Route
 * 获取自定义 URL 路由
 *
 * @param array|string $keys 数组时，表示 m=0, c=1, a=2；字符串时，表示获取键值
 * @param string $type URL 参数转换类型，默认为 'get'
 * @return array|string 返回路由数组或 GET 参数字符串
 */
function cR($keys = [], $type = 'get') {
    $param = sys::loadSysClass('Param');
    $param = new Param();
    if (is_array($keys)) {
        $routes = [];
        $keysCount = count($keys);

        // 处理 keys 数组中的每个元素
        for ($i = 0; $i < $keysCount; $i++) {
            if (!empty($keys[$i])) {
                $routes[$param->getRoute($i)] = $keys[$i];
            }
        }

        // 如果 keys 数量为 1，不补全其他默认值
        if ($keysCount == 1) {
            return $type == 'get' ? DEFAULTS . '?' . http_build_query([$param->getRoute(0) => $keys[0]]) : [$param->getRoute(0) => $keys[0]];
        }
        // 如果 keys 数量为 2，补全第二个默认值并显示第三个键值
        if ($keysCount == 2) {
            $routes[$param->getRoute(1)] = $param->route_config[$param->getRoute(1)];
            $routes[$param->getRoute(2)] = $keys[1];
        }
		// print_r($routes);
        return $type == 'get' ? DEFAULTS . '?' . http_build_query($routes) : $routes;
    } else {
        return $param->getRoute($keys);
    }
}


/**
 * 提示信息页面跳转
 * @param string $msg 提示信息
 * @param int $code 成功1 失败0
 * @param string/array  $url_forward 跳转地址
 * @param int $ms 跳转等待时间
 * @param int $datas 数据
 */
function message($msg, $code = false, $datas = '', $url_forward = '', $ms = 1250) {
    if($url_forward === HTTP_REFERER){
        $url_forward = html_entity_decode(remove_xss(safe_replace($url_forward)));
    }
	$code = $code ? 1 : 0;
	if(defined('IN_APP')) {
		$response = [
			'status'=> 200,
			'code'=>$code,
			'message'=>$msg,
			'ms'=> $ms,
		];
		if (!empty($url_forward)) {
			$response['url_forward'] = $url_forward;
		}
		if (!empty($datas)) {
			$response['data'] = $datas;
		}
		header('Content-Type: application/json; charset=UTF-8');
		
		echo json_encode($response,325);
	} else {
		// include(template('content', 'message'));
	}
	exit;
}

/**
 * 获取系统配置参数
 * @param string $string 配置
 * @return string
 */
function sysConfig($string) {
	return sys::loadEnv(CONFIG_PATH,'system',$string);
}


/**
 * 语言文件处理
 *
 * @param string $language 标示符
 * @param array $pars 转义的数组, 二维数组 ,'key1'=>'value1','key2'=>'value2',
 * @param string $modules 多个模块之间用半角逗号隔开，如：member,guestbook
 * @return string 语言字符
 */
function L($language = 'no_language', $pars = array(), $modules = '') {
    $Language = sys::loadSysClass('Language');
	return $Language->load($language, $pars,$modules);
}

/**
 * 获取当前页面完整URL地址
 */
function get_url() {
	$sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
	$php_self = $_SERVER['PHP_SELF'] ? safe_replace($_SERVER['PHP_SELF']) : safe_replace($_SERVER['SCRIPT_NAME']);
	$path_info = isset($_SERVER['PATH_INFO']) ? safe_replace($_SERVER['PATH_INFO']) : '';
	$relate_url = isset($_SERVER['REQUEST_URI']) ? safe_replace($_SERVER['REQUEST_URI']) : $php_self.(isset($_SERVER['QUERY_STRING']) ? '?'.safe_replace($_SERVER['QUERY_STRING']) : $path_info);
	return $sys_protocal.(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '').$relate_url;
}


/**
 * 数据库连接
 *
 * @param string $type 类型(mysqli,access,sqlite)
 * @param string $stash 配置名称
 * @param array $array 请求数组
 * @param callable|null $preprocess 可选的预处理函数
 * @return string 语言字符
 */

function joinData(string $type = 'mysqli', $stash = 'default', array $array = [], callable $preprocess = null) {
    // 加载配置文件
    $config = sys::loadEnv(CONFIG_PATH, 'database');
	
	//初始化类
	sys::loadSysClass('db_Archive', '', false);
	$cinfig_arra = $config[$type][$stash];
	$cinfig_arra['dbType'] = $type;
	// print_r($cinfig_arra);
	$object = new db_Archive($cinfig_arra); // 传递配置参数
	print_r($object);
    // 如果有预处理函数，则执行预处理
    if ($preprocess) {
		$preprocess($object, $array);
    }
    // 关闭连接
    $object->close();
    return true; // 返回一个标志，表示操作成功
}

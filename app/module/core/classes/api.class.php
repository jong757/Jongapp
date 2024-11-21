<?php
defined('IN_APP') or exit('No permission resources.');


class api {
	// public $siteid;
	
	public function __construct() {
		// $this->siteid = get_siteid();
		// pc_base::load_app_func('global','admin');
	}

	public function bt_config($ss = '') {
		
		return ['name'=>'jong'];
	}
	// /**
	//  * left Public Menu
	//  * 左侧公共菜单JSON(Jong)
	//  *
	//  * @return array 返回数组
	//  */
	// public function get_leftPublicMenu() {
	// 	$menu_db = pc_base::load_model('menu_model');
	// 	$menu = $menu_db->select();
	// 	$menu_l = [];
	// 	foreach ($menu as $key => $value) {
	// 		$data = !empty($value['data']) ? '&'.$value['data'] : '';
	// 		$menu_l[$key] = [
	// 			'id' => $value['id'],
	// 			'title' => L($value['name']),
	// 			'parentid' => $value['parentid'],
	// 			'href' => Gr([$value['m'],$value['c'],$value['a']]).$data,
	// 			'target'=>'_self',
	// 			'icon' => ''
	// 		];
	// 	}
	// 	 $result = [
	// 			'homeInfo' => [
	// 				'href' => Gr(['admin','public_main']),
	// 				'title' => L('first') // 添加缺少的值
	// 			],
	// 			'logoInfo' => [
	// 				'href' => '/',
	// 				'image' => SATAICS_PATH.'layui/images/logo.png',
	// 				'title' => L('website_manage')
	// 			],
	// 			'menuInfo' => buildTree($menu_l, 0 ,3) // 确保 $menu_l 是一个数组
	// 		];

	// 	return $result;
	// }

	// /**
	//  * common function menu
	//  * 公共功能菜单JSON(Jong)
	//  *
	//  * @return array 返回数组
	//  */
	// public function get_commonFunctionMenu() {
		
	// 	$menu_db = pc_base::load_model('menu_model');
	// 	$id = isset($_GET['id']) && trim($_GET['id']) ? intval($_GET['id']) : showmessage(L('parameters_error'),'?'.Gr(0).'=admin&'.Gr(1).'=index');
	// 	$menu = $menu_db->get_one(['id'=>$id]);
	// 	$data = !empty($menu['data']) ? '&'.$menu['data'] : '';
	// 	$result['id'] = $menu['id'];
	// 	$result['title'] = L($menu['name']);
	// 	$result['href'] = Gr([$menu['m'],$menu['c'],$menu['a']]).$data;
	// 	return $result;
	
	// }
	
	// /**
	//  * acquire Multiple Languages
	//  * 获取多语言JSON(Jong)
	//  *
	//  * @param string $_GET['lang'] 语言标识
	//  * @param string $_GET['lang_modo'] 语言模型
	//  * @return array 返回数组
	//  */
	// public function get_Languages() {
	// 	$config = include_once PC_PATH . 'languages' . DS . 'config.php';
	// 	$allowedLangs = array_keys($config['file_name']); // 允许的语言列表
	// 	$route_lang = isset($_GET['lang']) && in_array($_GET['lang'], $allowedLangs) ? $_GET['lang'] : 'zh-cn';
	// 	$allowedModos = array_keys($config['file_explan'][$route_lang]); // 允许的模型列表
	// 	$route_m = isset($_GET['lang_modo']) && in_array($_GET['lang_modo'], $allowedModos) ? $_GET['lang_modo'] : 'content';
	// 	// 构建文件路径
	// 	$basePath = PC_PATH . 'languages' . DS . $route_lang . DS;
	// 	$moduleFilePath = $basePath . $route_m . '.lang.php';
	// 	$systemFilePath = $basePath . 'system.lang.php';
	// 	// 初始化语言数组
	// 	$LANG = array();
	// 	// 加载语言文件
	// 	foreach ([$systemFilePath, $moduleFilePath] as $filePath) {
	// 		if (file_exists($filePath) && strpos(realpath($filePath), realpath($basePath)) === 0) {
	// 			require $filePath;
	// 		}
	// 	}
	// 	// 输出语言文件内容
	// 	return [$route_lang => $LANG];
	// }


}

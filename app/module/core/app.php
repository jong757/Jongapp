<?php
/**
 *  app.php 核心控制器
 *
 * @copyright			(C) 2024-2025 Jong 
 * @license				qq:3865176
 * @lastmodify			2024-11-14 
 */
// defined('IN_PHPCMS') or exit('No permission resources.');


class app {
	public function __construct() {
		// parent::__construct();

	}

	public function init () {
		// $js = sys::loadModel('model','core','admin_role');
		// debug::trace($js->select());
		// print_r($js);
		
		// $factory = sys::loadSysClass('factory');
		
		// // 配置数组
		// $config = array(
		//     'mysql' => array(
		//         'type' => 'mysql'
		//     )
		// );
		
		// debug::trace($app->init('mysql',$config));
		// debug::trace($app->get_instance($config));
		// debug::trace($app->get_instance_by_name('mysql'));
	
	
		//查询
		print_r(joins('database', 'default', [], function($db) {
			// 执行查询
			// $WHERE = "parentid=977";
			$result = $db->select('admin','*');
			// 处理查询结果
			if ($result->num_rows > 0) {
				while ($row = $result->fetch_assoc()) {
					print_r($row);
				}
			} else {
				echo "0 results";
			}
		}));

		
		
		// // 数据库配置
		// $config = [
		//     'database1' => [
		//         'type' => 'mysqli',
		//         'host' => 'localhost',
		//         'username' => 'root',
		//         'password' => 'root',
		//         'database' => 'phpcmsv97'
		//     ]
		// ];
		
		// // 获取工厂实例并设置配置
		// $factory = sys::loadSysClass('factory');
		// $factory_instance = $factory::get_instance($config);
		
		// // 获取数据库实例
		// $db = $factory_instance->get_resource('database1', 'database');
		
		// // 执行查询
		// $result = $db->select('v9_menu');
		
		// // 处理查询结果
		// if ($result->num_rows > 0) {
		//     while ($row = $result->fetch_assoc()) {
		//         echo "id: " . $row["id"] . " - Name: " . $row["name"] . "<br>";
		//     }
		// } else {
		//     echo "0 results";
		// }
		
		// // 关闭数据库连接
		// $db->close();
		

	

	

		echo '核心控制器<br>';
		
	}
	
}
<?php 
/**
 *  api.php API 入口
 * @author Jong
 * 内置api(说明)
 * /api.php/admin?action=bt_config
 * 模型 admin ,文件 app ,方法bt_config
 * 
 * 插件api
 * 
 */
//目录分隔符
define('DS', DIRECTORY_SEPARATOR);
 //程序根目录
define('PATHS', dirname(__FILE__).DS);

//加载核心
include PATHS.DS.'app'.DS.'sys.php';


//内置api
$info = str_replace('/','',$_SERVER['PATH_INFO']);
$info_if = isset($info) && trim($info) ? trim($info) : '';
$model = isset($_GET['model']) && trim($_GET['model']) ? safe_replace(trim($_GET['model'])) : $info_if;

if ($model) {
    $action = isset($_GET['action']) && trim($_GET['action']) ? safe_replace(trim($_GET['action'])) : '没有方法!';
    $ger_class = sys::loadAppClass('api', $model);
    if ($ger_class) {
        define('ROUTE_M', $model);
        define('ROUTE_C', 'api');
        define('ROUTE_A', $action);
        if (method_exists('api', $action)) {
            // 设置响应类型为JSON
            header('Content-Type: application/json; charset=UTF-8');
            $result = $ger_class->$action();
            $message = is_array($result) ? L('success') : L('failure');
            $status = is_array($result);
			print_r($status);
            message($message, $status, $result);
        } else {
            exit(json_encode(['code' => 1, 'msg' => 'The ' . $action . ' method does not exist in api.class.php!']));
        }
    } else {
        exit(json_encode(['code' => 1, 'msg' => 'The api.class.php class in the ' . $model . ' model does not exist!']));
    }
}


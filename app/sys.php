<?php
 /**
 *  sys.php 全局加载控制器 
 *
 * @copyright			(C) 2024-2025 Jong 
 * @license				qq:3865176
 * @lastmodify			2024-11-14 
 */

//当前核心目录路径
define('PATH', dirname(__FILE__).DS);

//加载常量库
sys::loadFunc('constant');

//加载公共方法库
sys::loadFunc('global');

class sys {
    /**
    * 创建应用程序实例(默认加载)
    */
    public static function app() {
        return self::loadSysClass('application');
    }

    /**
    * 加载系统类
    */
    public static function loadSysClass($class, $path = '', $init = true) {
        return self::loadItem($class, $path, $init, 'class.php');
    }
    
    /**
    * 加载模块类
    */
    public static function loadAppClass($class, $module = '', $init = true) {
        $module = empty($module) && defined('ROUTE_M') ? ROUTE_M : $module;
        return $module ? self::loadItem($class, self::getModPath($module, 'classes'), $init, 'class.php') : false;
    }
    
  //   /**
  //   * 加载数据模型
  //   */
  //   public static function loadModel($class, $model = '', $init = true) {
		
  //       $model = self::loadItem($class, '', $init, 'class.php');
		// return $model->table_name[$model];
		
  //   }
    
    /**
    * 加载函数库
    */
    public static function loadFunc($func, $path = '') {
        return self::loadItem($func, $path ?: 'lib' . DS . 'functions', false, 'func.php');
    }
	
	/**
	* 获取模块的路径
	*/
	private static function getModPath($module, $subdir = '') {
	    return 'module' . DS . $module . DS . $subdir;
	}
	
	
	/**
	 * 加载配置和资源
	 */
	public static function loadEnv($configPath, $file, $key = '', $default = '', $reload = false) {
		static $configs = [];
		if (!$reload && isset($configs[$file])) {
			return empty($key) ? $configs[$file] : ($configs[$file][$key] ?? $default);
		}
		
		$path = $configPath . $file . '.php';
		
		if (file_exists($path)) {
		    $configs[$file] = include $path;
		}
		// print_r($configs);
		
		return empty($key) ? $configs[$file] : ($configs[$file][$key] ?? $default);
	}

    /**
    * 加载类或文件
    */
    private static function loadItem($name, $path, $init, $ext) {
		static $loaded_items = [];
        // 默认路径处理
        $path = self::normPath($path ?: 'lib' . DS . 'classes');
        $file_path = $path . DS . $name . '.' . $ext;
        $key = md5($file_path);
        
        if (isset($loaded_items[$key])) {
            return $loaded_items[$key] === false ? false : $loaded_items[$key];
        }
        
        if (file_exists(PATH . $file_path)) {
            include PATH . $file_path;
            if ($ext === 'class.php') {
                $loaded_items[$key] = $init ? new $name : true;
                return $loaded_items[$key];
            } else {
                $loaded_items[$key] = true;
                return true;
            }
        }
        $loaded_items[$key] = false;
        return false;
    }

    /**
    * 标准化路径，避免重复拼接
    */
    private static function normPath($path) {
        return rtrim($path, DS);
    }
}

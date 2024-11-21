<?php
/**
 *  factory.class.php 通用工厂类
 */

final class factory {
	
	/**
	 * 当前工厂类静态实例
	 */
	private static $instance;
	
	/**
	 * 配置列表
	 */
	protected $config = [];
	
	/**
	 * 实例化列表
	 */
	protected $instances = [];
	
	/**
	 * 构造函数
	 */
	public function __construct() {
	}
	
	/**
	 * 返回当前终级类对象的实例
	 * @param array $config 配置
	 * @return factory
	 */
	public static function get_instance(array $config = []) {
		if (self::$instance === null) {
			self::$instance = new self();
		}
		if (!empty($config)) {
			self::$instance->config = array_merge(self::$instance->config, $config);
		}
		return self::$instance;
	}
	
	/**
	 * 获取资源操作实例
	 * @param string $name 资源配置名称
	 * @param string $type 资源类型（如'database'或'cache'）
	 * @return object
	 */
	public function get_resource(string $name, string $type) {
		$key = $type . '_' . $name;
		if (!isset($this->instances[$key])) {
			$this->instances[$key] = $this->connect($name, $type);
		}
		return $this->instances[$key];
	}
	
	/**
	 * 加载资源驱动
	 * @param string $name 资源配置名称
	 * @param string $type 资源类型
	 * @return object
	 */
	private function connect(string $name, string $type) {
		$object = null;
		switch ($type) {
			case 'database':
				$object = $this->connect_database($name);
				break;
			case 'cache':
				$object = $this->connect_cache($name);
				break;
			default:
				throw new Exception("Unsupported resource type: " . $type);
		}
		return $object;
	}

	/**
	 * 加载数据库驱动
	 * @param string $name 数据库配置名称
	 * @return object
	 */
	private function connect_database(string $name) {
	    $object = null;
	    switch ($this->config[$name]['type']) {
	        case 'mysqli':
	            sys::loadSysClass('db_mysqli', '', false);
	            $object = new db_mysqli($this->config[$name]); // 传递配置参数
	            break;
	        case 'access':
	            $object = sys::loadSysClass('db_access');
				 $object = new db_access($this->config[$name]);
				 $object->open($this->config[$name]);
	            break;
	        default:
	            sys::loadSysClass('db_mysqli', '', false);
	            $object = new db_mysqli($this->config[$name]);
	    }
	    $object->open($this->config[$name]);
	    return $object;
	}


	/**
	 * 加载缓存驱动
	 * @param string $name 缓存配置名称
	 * @return object
	 */
	private function connect_cache(string $name) {
		$object = null;
		switch ($this->config[$name]['type']) {
			case 'memcache':
				$object = new Memcache();
				$object->connect($this->config[$name]['host'], $this->config[$name]['port']);
				break;
			case 'redis':
				$object = new Redis();
				$object->connect($this->config[$name]['host'], $this->config[$name]['port']);
				break;
			default:
				throw new Exception("Unsupported cache type: " . $this->config[$name]['type']);
		}
		return $object;
	}

	/**
	 * 关闭所有资源连接
	 */
	private function close() {
		foreach ($this->instances as $instance) {
			$instance->close();
		}
	}
	
	/**
	 * 析构函数
	 */
	public function __destruct() {
		$this->close();
	}
}



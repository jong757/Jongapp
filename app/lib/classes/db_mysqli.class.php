<?php
/**
 * db_mysqli.class.php MySQLi数据库类
 */

class db_mysqli {
    /**
     * MySQLi连接实例
     */
    private $connection;

    /**
     * 标志连接是否已关闭
     */
    private $isClosed = false;
    
    /**
     * 数据表名前缀
     */
    private $tablepre = false;

    /**
     * 数据库名称
     */
    private $database = '';

    /**
     * 构造函数
     * @param array $config 数据库配置
     */
    public function __construct(array $config) {
        $this->open($config);
        $this->tablepre = $config['tablepre']; // 数据表前缀
        $this->database = $config['database']; // 数据库名称
    }

    /**
     * 打开数据库连接
     * @param array $config 数据库配置
     */
    public function open(array $config) {
        $this->connect($config);
    }

    /**
     * 连接到数据库
     * @param array $config 数据库配置
     */
    private function connect(array $config) {
        $this->connection = new mysqli(
            $config['host'],
            $config['username'],
            $config['password'],
            $config['database'],
            $config['port'] ?? 3306
        );

        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }
    }

    /**
     * 执行查询
     * @param string $query SQL查询
     * @return mixed 查询结果
     */
    public function query(string $query) {
        $this->ensureConnectionIsOpen();
        $this->logQuery($query);

        $result = $this->connection->query($query);
        
        if ($result === false) {
            die("Query failed: " . $this->connection->error);
        }

        return $result;
    }

    /**
     * 插入数据
     * @param string $table 表名
     * @param array $data 数据数组
     * @return bool 是否成功
     */
    public function insert(string $table, array $data) {
        $columns = implode(", ", array_keys($data));
        $values = implode(", ", array_map([$this->connection, 'real_escape_string'], array_values($data)));
        $query = "INSERT INTO {$this->tablepre}$table ($columns) VALUES ($values)";
        return $this->query($query);
    }

    /**
     * 更新数据
     * @param string $table 表名
     * @param array $data 数据数组
     * @param string $condition 更新条件
     * @return bool 是否成功
     */
    public function update(string $table, array $data, string $condition) {
        $set = array_map(function($column, $value) {
            return "$column = '" . $this->connection->real_escape_string($value) . "'";
        }, array_keys($data), $data);
        
        $setString = implode(", ", $set);
        $query = "UPDATE {$this->tablepre}$table SET $setString WHERE $condition";
        return $this->query($query);
    }

    /**
     * 删除数据
     * @param string $table 表名
     * @param string $condition 删除条件
     * @return bool 是否成功
     */
    public function delete(string $table, string $condition) {
        $query = "DELETE FROM {$this->tablepre}$table WHERE $condition";
        return $this->query($query);
    }

    /**
     * 查询数据
     * @param string $table 表名
     * @param string $columns 查询的列
     * @param string $condition 查询条件
     * @return mixed 查询结果
     */
    public function select(string $table, string $columns = "*", string $condition = "1") {
        $query = "SELECT $columns FROM {$this->tablepre}$table WHERE $condition";
        return $this->query($query);
    }
	
	/**
	 * 创建数据库和表
	 * @param string $table 表名
	 * @param array $fields 字段定义数组
	 * @param string $engine 数据库引擎类型
	 * @return bool 是否成功
	 */
	public function createDatabaseAndTable(string $table, array $fields, string $engine = 'InnoDB') {
	    // 创建数据库（如果不存在）
	    if (!$this->createDatabase()) {
	        return false;
	    }
	    // 构建创建表的SQL语句
	    $fieldsSql = implode(", ", $fields);
	    $createTableQuery = "CREATE TABLE {$this->tablepre}$table ($fieldsSql) ENGINE=$engine";
	
	    // 创建表
	    return $this->executeQuery($createTableQuery, "Table created successfully", "Error creating table");
	}
	
	/**
	 * 修改表结构，添加、修改或删除字段
	 * @param string $table 表名
	 * @param string $action 操作类型（'add', 'edit' 或 'del'）
	 * @param string $field 字段定义
	 * @param string $newField 新字段定义（仅用于修改字段）
	 * @return bool 是否成功
	 */
	public function modifyColumn(string $table, string $action, string $field, string $newField = '') {
	    // 构建修改字段的SQL语句
	    switch ($action) {
	        case 'add':
	            $modifyColumnQuery = "ALTER TABLE {$this->tablepre}$table ADD $field";
	            break;
	        case 'edit':
	            $modifyColumnQuery = "ALTER TABLE {$this->tablepre}$table CHANGE $field $newField";
	            break;
	        case 'del':
	            $modifyColumnQuery = "ALTER TABLE {$this->tablepre}$table DROP COLUMN $field";
	            break;
	        default:
	            echo "Invalid action specified<br>";
	            return false;
	    }

	    // 执行修改字段的查询
	    return $this->executeQuery($modifyColumnQuery, ucfirst($action) . " column successfully", "Error modifying column");
	}
	
	/**
	 * 创建数据库（如果不存在）
	 * @return bool 是否成功
	 */
	private function createDatabase() {
	    $createDbQuery = "CREATE DATABASE IF NOT EXISTS {$this->database}";
	    return $this->executeQuery($createDbQuery, "Database checked/created successfully", "Error creating database");
	}
	
	/**
	 * 执行查询并处理结果
	 * @param string $query SQL查询
	 * @param string $successMessage 成功消息
	 * @param string $errorMessage 错误消息
	 * @return bool 是否成功
	 */
	private function executeQuery(string $query, string $successMessage, string $errorMessage) {
	    if ($this->query($query) === TRUE) {
	        echo $successMessage . "<br>";
	        return true;
	    } else {
	        echo $errorMessage . ": " . $this->connection->error . "<br>";
	        return false;
	    }
	}




    /**
     * 关闭数据库连接
     */
    public function close() {
        if ($this->connection && !$this->isClosed) {
            $this->connection->close();
            $this->isClosed = true;
        }
    }

    /**
     * 析构函数
     */
    public function __destruct() {
        $this->close();
    }

    /**
     * 确保连接是打开的
     */
    private function ensureConnectionIsOpen() {
        if ($this->isClosed) {
            throw new Exception("Cannot execute query: connection is closed.");
        }
    }

    /**
     * 记录查询
     * @param string $query SQL查询
     */
    private function logQuery(string $query) {
        if (defined('APP_DEBUG') && APP_DEBUG) {
            debug::addmsg($query, 1);
        }
    }


}
?>

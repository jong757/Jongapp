<?php

/**
 * Class ApiResponse
 * 用于标准化API响应格式
 */
class ApiResponse {
    public $status;  // 响应状态码
    public $message; // 响应消息
    public $data;    // 响应数据

    /**
     * ApiResponse constructor.
     * @param int $status 响应状态码
     * @param string $message 响应消息
     * @param mixed $data 响应数据
     */
    public function __construct($status, $message, $data = null) {
        $this->status = $status;
        $this->message = $message;
        $this->data = $data;
    }

    /**
     * 将响应对象转换为JSON格式
     * @return string JSON格式的响应
     */
    public function toJson() {
        return json_encode($this);
    }
}

/**
 * Class Api
 * 处理API请求
 */
class Api {
    private $db; // 数据库连接对象

    /**
     * Api constructor.
     * @param PDO $db 数据库连接对象
     */
    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * 处理HTTP请求
     */
    public function handleRequest() {
        header('Content-Type: application/json'); // 设置响应头为JSON格式

        $method = $_SERVER['REQUEST_METHOD']; // 获取HTTP请求方法
        $endpoint = $_GET['endpoint'] ?? '';  // 获取请求的API端点

        // 根据请求方法调用相应的处理函数
        switch ($method) {
            case 'GET':
                $this->handleGet($endpoint);
                break;
            case 'POST':
                $this->handlePost($endpoint);
                break;
            case 'PUT':
                $this->handlePut($endpoint);
                break;
            case 'DELETE':
                $this->handleDelete($endpoint);
                break;
            default:
                $this->sendResponse(405, 'Method Not Allowed');
                break;
        }
    }

    /**
     * 处理GET请求
     * @param string $endpoint 请求的API端点
     */
    private function handleGet($endpoint) {
        // 处理GET请求的逻辑
        $this->sendResponse(200, 'GET request received', ['endpoint' => $endpoint]);
    }

    /**
     * 处理POST请求
     * @param string $endpoint 请求的API端点
     */
    private function handlePost($endpoint) {
        // 处理POST请求的逻辑
        $this->sendResponse(200, 'POST request received', ['endpoint' => $endpoint]);
    }

    /**
     * 处理PUT请求
     * @param string $endpoint 请求的API端点
     */
    private function handlePut($endpoint) {
        // 处理PUT请求的逻辑
        $this->sendResponse(200, 'PUT request received', ['endpoint' => $endpoint]);
    }

    /**
     * 处理DELETE请求
     * @param string $endpoint 请求的API端点
     */
    private function handleDelete($endpoint) {
        // 处理DELETE请求的逻辑
        $this->sendResponse(200, 'DELETE request received', ['endpoint' => $endpoint]);
    }

    /**
     * 发送API响应
     * @param int $status 响应状态码
     * @param string $message 响应消息
     * @param mixed $data 响应数据
     */
    private function sendResponse($status, $message, $data = null) {
        $response = new ApiResponse($status, $message, $data);
        echo $response->toJson();
    }
}

// // 示例数据库连接
// $db = new PDO('mysql:host=localhost;dbname=testdb', 'username', 'password');

// // 创建API实例并处理请求
// $api = new Api($db);
// $api->handleRequest();

?>

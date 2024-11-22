<?php

class ExceptionHandler
{
    private $logFile; // 日志文件路径
    private $friendlyMessage; // 用户友好的错误消息
    private $logLevel; // 日志级别
    private $context; // 上下文信息

    // 构造函数，初始化日志文件路径、用户友好的错误消息、日志级别和上下文信息
    public function __construct($logFile = "/var/log/my_app.log", $friendlyMessage = "Sorry, something went wrong. Please try again later.", $logLevel = 'ERROR', $context = [])
    {
        $this->logFile = $logFile;
        $this->friendlyMessage = $friendlyMessage;
        $this->logLevel = $logLevel;
        $this->context = $context;
    }

    // 处理异常的方法
    public function handleException($exception)
    {
        $this->logError($exception); // 记录错误日志
        $this->displayFriendlyError(); // 显示用户友好的错误信息
        $this->sendNotification($exception); // 发送通知（可选）
    }

    // 记录错误日志的方法
    private function logError($exception)
    {
        // 构建错误消息，包括时间戳、日志级别、异常信息、文件名、行号和堆栈跟踪
        $errorMessage = date('[Y-m-d H:i:s] ') . "[$this->logLevel] " . $exception->getMessage() . " in " . $exception->getFile() . " on line " . $exception->getLine() . "\nStack trace:\n" . $exception->getTraceAsString() . "\n";
        // 如果有上下文信息，添加到错误消息中
        if (!empty($this->context)) {
            $errorMessage .= "Context:\n" . print_r($this->context, true) . "\n";
        }
        // 将错误消息记录到日志文件中
        error_log($errorMessage, 3, $this->logFile);
    }

    // 显示用户友好的错误信息的方法
    private function displayFriendlyError()
    {
        echo $this->friendlyMessage; // 输出用户友好的错误消息
    }

    // 发送通知的方法（例如通过电子邮件、短信、Slack 等）
    private function sendNotification($exception)
    {
        // 这里可以添加发送通知的代码，例如通过电子邮件发送错误信息
        // mail($to, $subject, $message);
    }

    // 设置全局异常处理的方法
    public static function setGlobalHandler($logFile = "/var/log/my_app.log", $friendlyMessage = "Sorry, something went wrong. Please try again later.", $logLevel = 'ERROR', $context = [])
    {
        // 设置全局异常处理程序
        set_exception_handler([new self($logFile, $friendlyMessage, $logLevel, $context), 'handleException']);
    }
}

// // 设置全局异常处理
// ExceptionHandler::setGlobalHandler("/var/log/my_app.log", "Sorry, something went wrong. Please try again later.", 'ERROR', ['user' => 'current_user', 'url' => 'current_url']);

// // 示例：抛出一个异常
// throw new Exception("This is a test exception.");

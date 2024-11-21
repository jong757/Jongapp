<?php 

//token令牌验证
class Token {
    private $BT_KEY;
    private $request_token;
    private $request_time;

    public function __construct($request_token = null, $request_time = null) {
        $this->BT_KEY = sysConfig('Token');
        $this->request_token = $request_token;
        $this->request_time = $request_time;

        if (is_null($this->request_token)) {
            echo $this->handleResponse("没有TOKEN");
            exit;
        }
        
        if (is_null($this->request_time)) {
            echo $this->handleResponse("验证失败");
            exit;
        }

        $this->validateAndCheckToken();
    }
    
    // 验证并检查TOKEN
    private function validateAndCheckToken() {
        try {
            if ($this->request_token != hash('sha256', $this->request_time . '' . hash('sha256', $this->BT_KEY))) {
                throw new Exception("Token 不匹配");
            }
            // if (time() > $this->request_time) {
                // throw new Exception("Token 已过期");
            // }
            // 验证成功，可以进行其他操作
			return true;
        } catch (Exception $e) {
            echo $this->handleResponse($e->getMessage());
            exit;
        }
    }

    // 处理消息
    private function handleResponse($message, $status = false) {
        return json_encode(array('status' => $status, 'msg' => $message), 320);
    }
    
    // 公共方法：更新TOKEN
    public function UpdateToken() {
        try {
            $new_key = $this->setRandomPassword(32);
            $this->BT_KEY = $new_key;
            $this->saveTokenToConfig($new_key);
			//返回更新后的Token
            return $this->handleResponse($new_key, true);
        } catch (Exception $e) {
            return $this->handleResponse($e->getMessage());
        }
    }
	
	// // 公共方法：更新ICP备案号
 //    public function UpdateICP($icp) {
 //        try {
 //            $this->saveTokenToConfig($icp);
	// 		//返回更新后的ICP备案号
 //            return $this->handleResponse($icp, true);
 //        } catch (Exception $e) {
 //            return $this->handleResponse($e->getMessage());
 //        }
 //    }

    // 生成随机密码
    public function setRandomPassword($length) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $password = 'ks-';
        for ($i = 0; $i < $length; $i++) {
            $password .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $password;
    }

    // 保存Token或备案号到配置文件
    private function saveTokenToConfig($token) {
		$configfile = CACHE_PATH . 'configs' . DIRECTORY_SEPARATOR . 'system.php';
		if (!is_writable($configfile)) {
			throw new Exception('Please chmod ' . $configfile . ' to 0777 !');
		}

		// 判断是否为Token
		$isToken = substr($token, 0, 2) == 'ks' ? true : false;
		$key = $isToken ? 'Token' : 'ICP';

		$pattern = "/'".$key."'\s*=>\s*([']?)[^']*([']?)(\s*),/is";
		$replacement = "'".$key."' => \${1}" . addcslashes($token, '\\') . "\${2}\${3},";

		$str = file_get_contents($configfile);
		$str = preg_replace($pattern, $replacement, $str);

		if (pc_base::load_config('system', 'lock_ex')) {
			if (file_put_contents($configfile, $str, LOCK_EX) === false) {
				throw new Exception('Failed to write to ' . $configfile);
			}
		} else {
			if (file_put_contents($configfile, $str) === false) {
				throw new Exception('Failed to write to ' . $configfile);
			}
		}
    }

    // 控制调试信息
    public function __debugInfo() {
        return [
            'request_token' => $this->request_token,
            'request_time' => $this->request_time,
        ];
    }
}

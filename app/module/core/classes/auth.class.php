<?php
/**
 * Auth 类，包含用户登录和 QQ 登录功能
 */

class Auth {
    private $db;
    private $loginAttempts = 0;
    private $maxLoginAttempts = 5;
    private $ua = "Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/69.0.3497.100 Safari/537.36";

    public function __construct($db) {
        $this->db = $db;
    }

    public function login($username, $password) {
        $user = $this->getUserByUsername($username);
        if ($user && password_verify($password, $user['password'])) {
            if ($user['is_locked']) {
                echo "账户已被锁定，请稍后再试。";
                return false;
            }
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $this->resetLoginAttempts($username);
            return true;
        } else {
            $this->incrementLoginAttempts($username);
            if ($this->loginAttempts >= $this->maxLoginAttempts) {
                $this->lockAccount($username);
                echo "账户已被锁定，请稍后再试。";
            } else {
                echo "用户名或密码错误！";
            }
            return false;
        }
    }

    private function getUserByUsername($username) {
        try {
            $query = "SELECT * FROM {$this->db->tablepre}users WHERE username = :username";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching user: " . $e->getMessage());
            return null;
        }
    }

    private function incrementLoginAttempts($username) {
        $this->loginAttempts++;
        $this->updateLoginAttempts($username, 'login_attempts = login_attempts + 1');
    }

    private function resetLoginAttempts($username) {
        $this->loginAttempts = 0;
        $this->updateLoginAttempts($username, 'login_attempts = 0');
    }

    private function updateLoginAttempts($username, $queryPart) {
        $query = "UPDATE {$this->db->tablepre}users SET $queryPart WHERE username = :username";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
    }

    private function lockAccount($username) {
        $query = "UPDATE {$this->db->tablepre}users SET is_locked = 1 WHERE username = :username";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
    }

    public function logout() {
        session_unset();
        session_destroy();
    }

    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    public function getQqQrCode() {
        $url = 'https://ssl.ptlogin2.qq.com/ptqrshow?appid=549000912&e=2&l=M&s=4&d=72&v=4&t=0.5409099' . time() . '&daid=5';
        $arr = $this->makeRequest($url, true);
        preg_match('/qrsig=(.*?);/', $arr['header'], $match);
        $qrsig = $match[1] ?? '';
        if ($qrsig) {
            return ['code' => 1, 'msg' => '', 'data' => ['qrsig' => $qrsig, 'qrcode' => base64_encode($arr['body'])]];
        } else {
            return ['code' => 0, 'msg' => '二维码获取失败', 'data' => []];
        }
    }

    public function qqLogin($qrsig) {
        if (empty($qrsig)) {
            return ['code' => 0, 'msg' => 'qrsig不能为空', 'data' => []];
        }
        $url = 'https://ssl.ptlogin2.qq.com/ptqrlogin?u1=https%3A%2F%2Fqzs.qq.com%2Fqzone%2Fv5%2Floginsucc.html%3Fpara%3Dizone&ptqrtoken=' . $this->getQrtoken($qrsig) . '&login_sig=&ptredirect=0&h=1&t=1&g=1&from_ui=1&ptlang=2052&action=0-0-' . time() . '0000&js_ver=10194&js_type=1&pt_uistyle=40&aid=549000912&daid=5&';
        $ret = $this->makeRequest($url, false, 'qrsig=' . $qrsig . '; ');
        if (preg_match("/ptuiCB\('(.*?)'\)/", $ret, $arr)) {
            $r = explode("','", str_replace("', '", "','", $arr[1]));
            if ($r[0] == 0) {
                preg_match('/uin=(\d+)&/', $ret, $uin);
                $openid = $uin[1];
                $data = $this->makeRequest($r[2]);
                preg_match("/p_skey=(.*?);/", $data, $matchs);
                $pskey = $matchs[1] ?? null;

                if ($pskey) {
                    return ['code' => 1, 'msg' => '登录成功', 'data' => ['qq' => $openid, 'name' => $r[5]]];
                } else {
                    return ['code' => 0, 'msg' => '获取相关信息失败', 'data' => []];
                }
            } else {
                return $this->handleLoginError($r[0], $r[4]);
            }
        } else {
            return ['code' => 0, 'msg' => $ret, 'data' => []];
        }
    }

    private function handleLoginError($code, $msg) {
        $messages = [
            65 => '二维码已失效',
            66 => '二维码未失效',
            67 => '正在验证二维码'
        ];
        return ['code' => 0, 'msg' => $messages[$code] ?? $msg, 'data' => []];
    }

    private function getQrtoken($qrsig) {
        $hash = 0;
        foreach (str_split($qrsig) as $char) {
            $hash += (($hash << 5) & 2147483647) + ord($char) & 2147483647;
            $hash &= 2147483647;
        }
        return $hash & 2147483647;
    }

    private function makeRequest($url, $split = false, $cookie = '', $post = 0, $referer = 0, $header = 0, $ua = 0, $nobody = 0) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $httpheader = [
            "Accept: application/json",
            "Accept-Encoding: gzip,deflate,sdch",
            "Accept-Language: zh-CN,zh;q=0.8",
            "Connection: keep-alive"
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $httpheader);
        if ($post) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }
        if ($header) {
            curl_setopt($ch, CURLOPT_HEADER, true);
        }
        if ($cookie) {
            curl_setopt($ch, CURLOPT_COOKIE, $cookie);
        }
        if ($referer) {
            curl_setopt($ch, CURLOPT_REFERER, $referer);
        }
        curl_setopt($ch, CURLOPT_USERAGENT, $ua ?: $this->ua);
        if ($nobody) {
            curl_setopt($ch, CURLOPT_NOBODY, 1);
        }
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_ENCODING, "gzip");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $ret = curl_exec($ch);
        if ($split) {
            $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            $header = substr($ret, 0, $headerSize);
            $body = substr($ret, $headerSize);
            $ret = ['header' => $header, 'body' => $body];
        }
        curl_close($ch);
        return $ret;
    }
}
?>

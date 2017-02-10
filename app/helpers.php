<?php

// 调试变量
if (!function_exists('dump')) {

    function dump($var, $echo = true, $label = null, $strict = true) {
        $label = ($label === null) ? '' : rtrim($label) . ' ';
        if (!$strict) {
            if (ini_get('html_errors')) {
                $output = print_r($var, true);
                $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
            } else {
                $output = $label . print_r($var, true);
            }
        } else {
            ob_start();
            var_dump($var);
            $output = ob_get_clean();
            if (!extension_loaded('xdebug')) {
                $output = preg_replace("/\]\=\>\n(\s+)/m", '] => ', $output);
                $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
            }
        }
        if ($echo) {
            echo($output);
            return null;
        } else {
            return $output;
        }
    }

}

// 获取外网真实IP
if (!function_exists('get_ip')) {

    function get_ip() {
        if (isset($_SERVER['HTTP_QVIA'])) {
            $ip = qvia2ip($_SERVER['HTTP_QVIA']);
            if ($ip) {
                return trim($ip);
            }
        }
        if (isset($_SERVER['HTTP_CLIENT_IP']) && !empty($_SERVER['HTTP_CLIENT_IP'])) {
            return checkIP($_SERVER['HTTP_CLIENT_IP']) ? trim($_SERVER['HTTP_CLIENT_IP']) : '0.0.0.0';
        }
        if (isset($_SERVER['HTTP_TRUE_CLIENT_IP']) && !empty($_SERVER['HTTP_TRUE_CLIENT_IP'])) {
            return checkIP($_SERVER['HTTP_TRUE_CLIENT_IP']) ? $_SERVER['HTTP_TRUE_CLIENT_IP'] : '0.0.0.0';
        }
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = strtok($_SERVER['HTTP_X_FORWARDED_FOR'], ',');
            do {
                $tmpIp = explode('.', $ip);
                if (is_array($tmpIp) && count($tmpIp) == 4) {
                    if (($tmpIp[0] != 10) && ($tmpIp[0] != 172) && ($tmpIp[0] != 192) && ($tmpIp[0] != 127) && ($tmpIp[0] != 255) && ($tmpIp[0] != 0)) {
                        return trim($ip);
                    }
                    if (($tmpIp[0] == 172) && ($tmpIp[1] < 16 || $tmpIp[1] > 31)) {
                        return trim($ip);
                    }
                    if (($tmpIp[0] == 192) && ($tmpIp[1] != 168)) {
                        return trim($ip);
                    }
                    if (($tmpIp[0] == 127) && ($ip != '127.0.0.1')) {
                        return trim($ip);
                    }
                    if ($tmpIp[0] == 255 && ($ip != '255.255.255.255')) {
                        return trim($ip);
                    }
                    if ($tmpIp[0] == 0 && ($ip != '0.0.0.0')) {
                        return trim($ip);
                    }
                }
            } while (($ip = strtok(',')));
        }
        if (isset($_SERVER['HTTP_PROXY_USER']) && !empty($_SERVER['HTTP_PROXY_USER'])) {
            return checkIP($_SERVER['HTTP_PROXY_USER']) ? trim($_SERVER['HTTP_PROXY_USER']) : '0.0.0.0';
        }

        if (isset($_SERVER['REMOTE_ADDR']) && !empty($_SERVER['REMOTE_ADDR'])) {
            return checkIP($_SERVER['REMOTE_ADDR']) ? trim($_SERVER['REMOTE_ADDR']) : '0.0.0.0';
        } else {
            return '0.0.0.0';
        }
    }

    /**
     * 获取网通代理或教育网代理带过来的客户端IP
     * @return        string/flase    IP串或false
     */
    function qvia2ip($qvia) {
        if (strlen($qvia) != 40) {
            return false;
        }
        $ips   = array(hexdec(substr($qvia, 0, 2)), hexdec(substr($qvia, 2, 2)), hexdec(substr($qvia, 4, 2)), hexdec(substr($qvia, 6, 2)));
        $ipbin = pack('CCCC', $ips[0], $ips[1], $ips[2], $ips[3]);
        $m     = md5('QV^10#Prefix' . $ipbin . 'QV10$Suffix%');
        if ($m == substr($qvia, 8)) {
            return implode('.', $ips);
        } else {
            return false;
        }
    }

    /**
     * 验证ip地址
     * @param        string    $ip, ip地址
     * @return        bool    正确返回true, 否则返回false
     */
    function checkIP($ip) {
        $ip = trim($ip);
        $pt = '/^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/';
        if (preg_match($pt, $ip) === 1) {
            return true;
        }
        return false;
    }

}




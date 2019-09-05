<?php
/**
 * GITHUB登录
 * midoks <midoks@163.com>
 * github: https://github.com/midoks/xiuno_github_login
 * doc: https://developer.github.com/apps/building-oauth-apps/authorizing-oauth-apps/#web-application-flow
 */

/**
 * Github PHP-SDK, 官方API部分

 */
class GithubApi {

    public $url           = 'https://github.com';
    public $api_url       = 'https://api.github.com';
    public $client_id     = '';
    public $client_secret = '';
    public $app_name      = '';
    public $scope         = 'repo, user';
    public function __construct() {
        $conf                = kv_get('xiuno_github_login');
        $this->client_id     = $conf['client_id'];
        $this->client_secret = $conf['client_secret'];
        $this->app_name      = $conf['app_name'];
    }

    public function url($addr) {
        return $this->url . $addr;
    }

    public function api($addr) {
        return $this->api_url . $addr;
    }

    public function jump($redirect_uri) {
        $code = $this->getRandomStr();
        $url  = $this->url('/login/oauth/authorize') . "?client_id={$this->client_id}&client_secret={$this->client_secret}&code={$code}&redirect_uri={$redirect_uri}&scope={$this->scope}";
        return $url;
    }

    public function getAccessToken($code) {
        $url  = $this->url('/login/oauth/access_token');
        $data = $this->httpPost($url, [
            'client_id'     => $this->client_id,
            'client_secret' => $this->client_secret,
            'code'          => $code,
        ]);
        parse_str($data);
        return $access_token;
    }

    public function getUser($access_token) {
        $url = $this->api('/user') . '?access_token=' . $access_token;
        // $headers[] = 'Authorization: token ' . $access_token;
        $headers[] = 'User-Agent: ' . $this->app_name;
        // $headers[] = 'Content-Type: application/json; charset=utf-8';
        // $headers[] = 'Access-Control-Allow-Origin: *';

        $data = $this->httpGet($url, $headers);
        return json_decode($data, true);
    }

    /**
     * POST 请求
     * @param string $url
     * @param array $param
     * @param boolean $post_file 是否文件上传
     * @return string content
     */
    private function httpPost($url, $param = [], $headers = [], $post_file = false) {
        $oCurl = curl_init();
        if (stripos($url, "https://") !== FALSE) {
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($oCurl, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1
        }
        if (PHP_VERSION_ID >= 50500 && class_exists('\CURLFile')) {
            $is_curlFile = true;
        } else {
            $is_curlFile = false;
            if (defined('CURLOPT_SAFE_UPLOAD')) {
                curl_setopt($oCurl, CURLOPT_SAFE_UPLOAD, false);
            }
        }
        if (is_string($param)) {
            $strPOST = $param;
        } elseif ($post_file) {
            if ($is_curlFile) {
                foreach ($param as $key => $val) {
                    if (substr($val, 0, 1) == '@') {
                        $param[$key] = new \CURLFile(realpath(substr($val, 1)));
                    }
                }
            }
            $strPOST = $param;
        } else {
            $aPOST = array();
            foreach ($param as $key => $val) {
                $aPOST[] = $key . "=" . urlencode($val);
            }
            $strPOST = join("&", $aPOST);
        }

        if (!empty($headers)) {
            curl_setopt($oCurl, CURLOPT_HEADER, $headers);
        }

        if (!empty($param)) {
            curl_setopt($oCurl, CURLOPT_POST, true);
            curl_setopt($oCurl, CURLOPT_POSTFIELDS, $strPOST);
        }

        curl_setopt($oCurl, CURLOPT_URL, $url);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);

        $sContent = curl_exec($oCurl);
        return $sContent;
    }

    public function httpGet($url, $header = []) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        if (!empty($header)) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        unset($curl);
        return $output;
    }

    /**
     * 随机生成16位字符串
     * @return string 生成的字符串
     */
    function getRandomStr() {

        $str     = "";
        $str_pol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $max     = strlen($str_pol) - 1;
        for ($i = 0; $i < 16; $i++) {
            $str .= $str_pol[mt_rand(0, $max)];
        }
        return $str;
    }

}

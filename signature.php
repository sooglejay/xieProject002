<?php

class Signature extends App
{
    /**
     * Signature constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $url = '';
        $code = '';
        if (isset($_GET['url'])) $url = $_GET['url'];
        if (isset($_GET['code'])) $code = $_GET['code'];
        if (!is_null($url)) $this->configUrl($url);
        if (!is_null($code)) $this->configOpenId($code);
    }

    private function configUrl($url)
    {
        $wxconfig = shell_exec("java -jar signature_jar.jar " . $url);
        header('Access-Control-Allow-Origin: *');
        echo json_encode($wxconfig);
    }

    private function configOpenId($code)
    {
        $ch = curl_init();
        $timeout = 500;
        curl_setopt($ch, CURLOPT_URL, 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=wxb76c5258ffa59386&secret=80b8d4e879263070071d9736d511f3b5&code=' . $code . '&grant_type=authorization_code');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $file_contents = curl_exec($ch);
        curl_close($ch);
        $myfile = fopen("save_code.txt", "w") or die("Unable to open file!");
        fwrite($myfile, $file_contents);
        echo $file_contents;
    }
}

new Signature();

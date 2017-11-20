<?php

class ValidationApp
{
    /**
     * 次方法只是用于微信的接入验证，验证过后，就不用了。
     */
    public function checkSignature()
    {
        $signature = $_GET['signature']; //微信加密签名
        $timestamp = $_GET['timestamp']; //时间戳
        $nonce = $_GET['nonce']; //随机数
        $echostr = $_GET['echostr']; //随机字符串
        $token = "2Lmm5cxiMMKSoJHb5r4P";
        $tempArr = array($token, $timestamp, $nonce);
        sort($tempArr);
        if ($signature == sha1(implode($tempArr))) {
            echo $echostr;
        } else {
            exit();
        }
    }

    /***
     * demo constructor.
     */
    public function __construct()
    {
        if (isset($_GET["signature"])) {
            $this->checkSignature();
            $data = $_GET["signature"];
            file_put_contents(dirname(__FILE__) . '/signature.txt', json_encode($data));

            return;
        }
        $data = file_get_contents("php://input");
        file_put_contents(dirname(__FILE__) . '/bazhong_input.txt', json_encode($data));

    }
}

$r = new ValidationApp();

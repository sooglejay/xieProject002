<?php

/**
 * 这个类是对接微信公众号平台的，之前一直是别人的服务器，所以微信只给他发
 * 现在换做了我们的了，我们就终于可以获取openid了！
 * Class demo
 */
class demo
{
    private $fromUsername;
    private $toUsername;
    private $keyword;

    /**
     * 次方法只是用于微信的接入验证，验证过后，就不用了。
     */
    public function checkSignature()
    {
        $signature = $_GET['signature']; //微信加密签名
        $timestamp = $_GET['timestamp']; //时间戳
        $nonce = $_GET['nonce']; //随机数
        $echostr = $_GET['echostr']; //随机字符串
        $token = "ZiYanWeiXinGongZhongHaoToken";
        $tempArr = array($token, $timestamp, $nonce);
        sort($tempArr);
        if ($signature == sha1(implode($tempArr))) {
            echo $echostr;
        } else {
            exit();
        }
    }


    private function responseMsg()
    {

    }

    /**
     * demo constructor.
     */
    public function __construct()
    {
        $data = file_get_contents("php://input");
        $xml = simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA);
        file_put_contents(dirname(__FILE__) . '/../test.txt', print_r($xml, true));
        if (isset($xml)) {
            $this->fromUsername = $xml->FromUserName;
            $this->toUsername = $xml->ToUserName;
            $this->keyword = trim($xml->Content);
        }
        if (isset($_GET["signature"])) {
            $this->checkSignature();
        } else {
            $this->responseMsg();
        }
    }
}

new demo();

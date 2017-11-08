<?php

/**
 * 这个类是对接微信公众号平台的，之前一直是别人的服务器，所以微信只给他发
 * 现在换做了我们的了，我们就终于可以获取openid了！
 * Class demo
 */
class demo
{
    private $fileCachePath;
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

    /***
     * demo constructor.
     */
    public function __construct()
    {
        $this->fileCachePath = dirname(__FILE__) . '/../file_cache/openId.txt';
        $wholeFile = dirname(__FILE__) . '/../file_cache/wholeText.txt';
        $data = file_get_contents("php://input");
        $xml = simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA);
        file_put_contents($wholeFile, json_encode($xml));
        if (isset($xml) &&
            isset($xml->FromUserName) &&
            isset($xml->ToUserName) &&
            isset($xml->Content)
        ) {
            $this->fromUsername = $xml->FromUserName;
            $this->toUsername = $xml->ToUserName;
            $this->keyword = trim($xml->Content);
            $arr = array(
                "openId" => $this->fromUsername
            );
            $this->writeArrayToFile($arr);
        }
        if (isset($_GET["signature"])) {
            $this->checkSignature();
        }
    }

    public function writeArrayToFile($arr)
    {
        file_put_contents($this->fileCachePath, json_encode($arr));
    }

    public function getArrayFromFile()
    {
        return json_decode(file_get_contents($this->fileCachePath), true);
    }
}

$r = new demo();

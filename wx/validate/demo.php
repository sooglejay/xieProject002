<?php

require_once dirname(__FILE__) . "/Core/Wechat.php";

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
        $data = file_get_contents("php://input");
        $xml = simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA);
        if (isset($xml) &&
            isset($xml->Content) && trim($xml->Content == "摸底123")
        ) {
            $openId = $this->fromUsername;
            $content = urlencode("请点击http://test.sighub.com/ziyan?openId=" . $openId);

            $accessToken = Wechat::accessToken();
            $url = "https://api.weixin.qq.com/customservice/kfaccount/add?access_token=" . $accessToken->access_token;
            $arr = array(
                "touser" => $openId,
                "msgtype" => "text",
                "text" => array("content" => $content)
            );
            $this->httpRequest($url, urldecode(json_encode($arr)));
        }
        if (isset($_GET["signature"])) {
            $this->checkSignature();
        }
    }

    private function httpRequest($url, $data = null)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        if (!is_null($data)) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
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

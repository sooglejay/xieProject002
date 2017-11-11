<?php
//
//require_once dirname(__FILE__) . "/Core/Wechat.php";
//
///**
// * 这个类是对接微信公众号平台的，之前一直是别人的服务器，所以微信只给他发
// * 现在换做了我们的了，我们就终于可以获取openid了！
// * Class demo
// */
//class demo
//{
//    private $fileCachePath;
//    private $fromUsername;
//    private $toUsername;
//    private $keyword;
//
//    /**
//     * 次方法只是用于微信的接入验证，验证过后，就不用了。
//     */
//    public function checkSignature()
//    {
//        $signature = $_GET['signature']; //微信加密签名
//        $timestamp = $_GET['timestamp']; //时间戳
//        $nonce = $_GET['nonce']; //随机数
//        $echostr = $_GET['echostr']; //随机字符串
//        $token = "ZiYanWeiXinGongZhongHaoToken";
//        $tempArr = array($token, $timestamp, $nonce);
//        sort($tempArr);
//        if ($signature == sha1(implode($tempArr))) {
//            echo $echostr;
//        } else {
//            exit();
//        }
//    }
//
//    /***
//     * demo constructor.
//     */
//    public function __construct()
//    {
//        $data = file_get_contents("php://input");
//        $xml = simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA);
//        if (isset($xml) &&
//            isset($xml->Content) && trim($xml->Content == "摸底123")
//        ) {
//            $openId = $this->fromUsername;
//            $content = urlencode("请点击http://test.sighub.com/ziyan?openId=" . $openId);
//
//            $accessToken = "ZKl-Eh2zMVTKHUhgARdOz2IlP8DXUEwpzlj_PjQgqKeKnhdo1J7sj2SPhVfSMJ4P352li3xTzdyPNxH8JVQgVZMSHMLk1qGqhuB2kY2ySekvbsdMtGBSt__LEZC2F9nmEQBjCHAIUF";
//            $url = "https://api.weixin.qq.com/customservice/kfaccount/add?access_token=" . $accessToken;
//            $arr = array(
//                "touser" => $openId,
//                "msgtype" => "text",
//                "text" => array("content" => $content)
//            );
//            echo 1234;
//            $this->httpRequest($url, urldecode(json_encode($arr)));
//        }
//        if (isset($_GET["signature"])) {
//            $this->checkSignature();
//        }
//    }
//
//    private function httpRequest($url, $data = null)
//    {
//        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL, $url);
//        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
//
//        if (!is_null($data)) {
//            curl_setopt($ch, CURLOPT_POST, 1);
//            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
//        }
//
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//        $output = curl_exec($ch);
//        curl_close($ch);
//        return $output;
//    }
//
//    public function writeArrayToFile($arr)
//    {
//        file_put_contents($this->fileCachePath, json_encode($arr));
//    }
//
//    public function getArrayFromFile()
//    {
//        return json_decode(file_get_contents($this->fileCachePath), true);
//    }
//}
//
//$r = new demo();

//引入库
require_once(dirname(__FILE__) . '/WXSDK/Weixin.php');
//使用init方法创建SDK实例
Weixin::init('ZiYanWeiXinGongZhongHaoToken', 'wxb76c5258ffa59386', '80b8d4e879263070071d9736d511f3b5', false);
/**
 * 监听用户消息
 * 用DemoClass类的subscribe方法处理 事件-订阅
 * 用DemoClass类的otherEvent方法处理其余事件
 * 用匿名函数处理location消息
 * 用catchAll函数处理其余消息
 */
$class = new Demo();
$location = function ($data) {
    //使用instance方法获取已经创建好的weixin实例
    Weixin::instance()->responseText('Hello World,这是一条位置消息,你的位置为' . $data->Label);
};
Weixin::instance()->setCallback(Weixin::TYPE_UNDEFINED, 'catchAll')
    ->setCallback(array(Weixin::TYPE_EVENT, Weixin::EVENT_SUBSCRIBE), array($class, 'subscribe'))
    ->setCallback(Weixin::TYPE_EVENT, array($class, 'otherEvent'))
    ->setCallback(Weixin::TYPE_LOCATION, $location)
    ->listen();

function catchAll(WeixinResult $data)
{
    $weixin = Weixin::instance();
    if ($data->Content == "摸底") {
        $articles = array(
            'title' => "商铺信息登记系统",
            'picurl' => 'http://test.sighub.com/ziyan/shop/image/pic_url.jpg',
            'url' => 'http://test.sighub.com/ziyan?openId=' . $data->FromUserName
        );
        $weixin->responseNews(array($articles));
    }
}

class Demo
{

    function __construct()
    {
        $this->weixin = Weixin::instance();
    }

    function subscribe(WeixinResult $data)
    {
        $this->weixin->responseText('终于等到你～欢迎订阅资阳移动微生活，精彩生活，从现在开始～');
    }

    function otherEvent(WeixinResult $data)
    {
        $this->weixin->responseText('其他事件:' . $data->Event);
    }

}


<?php

//include_once "wxBizMsgCrypt.php";

class demo
{

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

//    function doCheck($signature, $timeStamp, $nonce, $echostr)
//    {
//        // 第三方发送消息给公众平台
//        $encodingAesKey = "SRNPxS03InrOhHS0WSSLqe8VkQLccJvnLfsZoizgesD";
//        $token = "asvw5tsdfgvw456e";
//        $appId = "wxb76c5258ffa59386";
////        $text = "<xml><ToUserName><![CDATA[oia2Tj我是中文jewbmiOUlr6X-1crbLOvLw]]></ToUserName><FromUserName><![CDATA[gh_7f083739789a]]></FromUserName><CreateTime>1407743423</CreateTime><MsgType><![CDATA[video]]></MsgType><Video><MediaId><![CDATA[eYJ1MbwPRJtOvIEabaxHs7TX2D-HV71s79GUxqdUkjm6Gs2Ed1KF3ulAOA9H1xG0]]></MediaId><Title><![CDATA[testCallBackReplyVideo]]></Title><Description><![CDATA[testCallBackReplyVideo]]></Description></Video></xml>";
////
//        $pc = new WXBizMsgCrypt($token, $encodingAesKey, $appId);
////        $encryptMsg = '';
//        $errCode = $pc->encryptMsg($text, $timeStamp, $nonce, $encryptMsg);
////        if ($errCode == 0) {
////            print("加密后: " . $encryptMsg . "\n");
////        } else {
////            print($errCode . "\n");
////        }
//
//        $xml_tree = new DOMDocument();
//        $xml_tree->loadXML($encryptMsg);
//        $array_e = $xml_tree->getElementsByTagName('Encrypt');
//        $array_s = $xml_tree->getElementsByTagName('MsgSignature');
//        $encrypt = $array_e->item(0)->nodeValue;
//        $msg_sign = $array_s->item(0)->nodeValue;
//
//        $format = "<xml><ToUserName><![CDATA[toUser]]></ToUserName><Encrypt><![CDATA[%s]]></Encrypt></xml>";
//        $from_xml = sprintf($format, $encrypt);
//
//        // 第三方收到公众号平台发送的消息
//        $msg = '';
//        $errCode = $pc->decryptMsg($msg_sign, $timeStamp, $nonce, $from_xml, $msg);
//        if ($errCode == 0) {
//            print("解密后: " . $msg . "\n");
//        } else {
//            print($errCode . "\n");
//        }
//
//    }

    /**
     * demo constructor.
     */
    public function __construct()
    {
        $this->checkSignature();
    }
}

new demo();

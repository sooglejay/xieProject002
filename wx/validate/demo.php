<?php

include_once dirname(__FILE__) . "/wxBizMsgCrypt.php";

class demo
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
        $token = "ZiYanWeiXinGongZhongHaoToken";
        $tempArr = array($token, $timestamp, $nonce);
        sort($tempArr);
        if ($signature == sha1(implode($tempArr))) {
            echo $echostr;
        } else {
            exit();
        }
    }


    public function responseMsg()
    {
        /*
        获得请求时POST:XML字符串
        不能用$_POST获取，因为没有key
         */
        $xml_str = $GLOBALS['HTTP_RAW_POST_DATA'];
        if (empty($xml_str)) {
            die('');
        }
        if (!empty($xml_str)) {
            // 解析该xml字符串，利用simpleXML
            libxml_disable_entity_loader(true);
            //禁止xml实体解析，防止xml注入
            $request_xml = simplexml_load_string($xml_str, 'SimpleXMLElement', LIBXML_NOCDATA);
            $reArr = array("FromUserName" => $request_xml->FromUserName,
                "ToUserName" => $request_xml->ToUserName,
                "MsgType" => $request_xml->MsgType,
                "Content" => $request_xml->Content,
                "MsgId" => $request_xml->MsgId,
                "CreateTime" => $request_xml->CreateTime);
            file_put_contents(dirname(__FILE__) . '/../test.txt', print_r($reArr, true));

//            //判断该消息的类型，通过元素MsgType
//            switch ($request_xml->MsgType) {
//                case 'event':
//                    //判断具体的时间类型（关注、取消、点击）
//                    $event = $request_xml->Event;
//                    if ($event == 'subscribe') { // 关注事件
//                        $this->_doSubscribe($request_xml);
//                    } elseif ($event == 'CLICK') {//菜单点击事件
//                        $this->_doClick($request_xml);
//                    } elseif ($event == 'VIEW') {//连接跳转事件
//                        $this->_doView($request_xml);
//                    } else {
//
//                    }
//                    break;
//                case 'text'://文本消息
//                    $this->_doText($request_xml);
//                    break;
//                case 'image'://图片消息
//                    $this->_doImage($request_xml);
//                    break;
//                case 'voice'://语音消息
//                    $this->_doVoice($request_xml);
//                    break;
//                case 'video'://视频消息
//                    $this->_doVideo($request_xml);
//                    break;
//                case 'shortvideo'://短视频消息
//                    $this->_doShortvideo($request_xml);
//                    break;
//                case 'location'://位置消息
//                    $this->_doLocation($request_xml);
//                    break;
//                case 'link'://链接消息
//                    $this->_doLink($request_xml);
//                    break;
//            }
        }


    }

    /**
     * demo constructor.
     */
    public function __construct()
    {
        $this->responseMsg();
    }
}

new demo();

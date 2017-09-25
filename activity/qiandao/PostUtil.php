<?php

/**
 * Created by PhpStorm.
 * User: sooglejay
 * Date: 17/9/25
 * Time: 10:47
 */
require_once dirname(__FILE__) . "/AESUtil.php";

class PostUtil
{
//{"Lid":1,"Password":"e10adc3949ba59abbe56e057f20f883e","mobile":"15928620723","userId":"user"}
    private $Password = 'e10adc3949ba59abbe56e057f20f883e';
    private $Lid = 1;
    private $userId = "user";
    private $key = "liangshanyidong!@#$%12345^&*9678";

    private $aesUtil;

    /**
     * PostUtil constructor.
     */
    public function __construct()
    {

        $this->aesUtil = new AESUtil();
    }

    public function doPost($mobilePhone)
    {
        $json = json_encode(array("Password" => $this->Password,
            "userId" => $this->userId,
            "Lid" => $this->Lid,
            "mobile" => $mobilePhone
        ));
        $entry = $this->aesUtil->decryptAES($json, $this->key);
        $curl = new Curl\Curl();
        $url = "http://211.149.234.57:5818/Interface.aspx?Data=" . $entry;
        $curl->post($url);
        $bb = $curl->response;
        return $bb;
    }
}
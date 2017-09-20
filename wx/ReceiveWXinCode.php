<?php

/**
 * Created by PhpStorm.
 * User: sooglejay
 * Date: 17/9/14
 * Time: 20:09
 * 这个类是给微盟做对接的，比如获取access_token后，刷新access_token
 * 其实并没有什么卵用，因为获取不到openid
 *
 */
require_once dirname(__FILE__).'/../vendor/curl/curl/src/Curl/Curl.php';

class ReceiveWXinCode
{
    /**
     * only need do once
     */
    public function getAccessToken()
    {
        $curl = new Curl\Curl();
        $url = "https://dopen.weimob.com/fuwu/c/oauth2/token?code=A6FoOf&grant_type=authorization_code&client_id=F9427D8838E07BD463872C6C99574354&client_secret=48BF17DD2C5D551B8E67F7E362DF6FFB&redirect_uri=http://test.sighub.com/ziyan/wx/ReceiveWXinCode.php";
        $curl->post($url);
        $bb = $curl->response;
        $array = json_decode($bb, true);
        $this->w($array);
    }

    public function getOpenIdAndRefreshToken()
    {
        $curl = new Curl\Curl();
        $url = "http://dopen.weimob.com/fuwu/c/oauth2/token?grant_type=refresh_token&client_id=F9427D8838E07BD463872C6C99574354&client_secret=48BF17DD2C5D551B8E67F7E362DF6FFB&refresh_token=796018ea-8af9-4ce4-a647-60056188cfbf748";
        $curl->post($url);
        $bb = $curl->response;
        $array = json_decode($bb, true);
        $array["jiangwei"]="shide,wo shi jiangwei";
        $this->w($array);
        return $array["openId"];
    }

    private function w($resArr)
    {
        file_put_contents(dirname(__FILE__).'/test.txt', print_r($resArr, true));
    }

    /**
     * ReceiveWXinAccessToken constructor.
     */
    public function __construct()
    {
    }
}

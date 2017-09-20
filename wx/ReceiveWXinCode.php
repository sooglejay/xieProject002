<?php

/**
 * Created by PhpStorm.
 * User: sooglejay
 * Date: 17/9/14
 * Time: 20:09
 */
require_once dirname(__FILE__).'/../vendor/curl/curl/src/Curl/Curl.php';

class ReceiveWXinCode
{
    private function unUsed()
    {
        $access_token = -1;
        $token_type = -1;
        $expires_in = -1;
        $refresh_token = -1;
        $refresh_token_expires_in = -1;
        $scope = -1;
        $business_id = -1;
        $public_account_id = -1;
        $code = -1;
        $state = -1;

        $res = array();
        $this->set('access_token', $access_token, $res);
        $this->set('token_type', $token_type, $res);
        $this->set('expires_in', $expires_in, $res);
        $this->set('refresh_token', $refresh_token, $res);
        $this->set('refresh_token_expires_in', $refresh_token_expires_in, $res);
        $this->set('scope', $scope, $res);
        $this->set('business_id', $business_id, $res);
        $this->set('public_account_id', $public_account_id, $res);
        $this->set('code', $code, $res);
        $this->set('state', $state, $res);
        $this->w($res);
    }

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

    private function set($name, &$value, &$res)
    {
        if (isset($_REQUEST[$name])) {
            $value = $_REQUEST[$name];
            $res[$name] = $value;
        }
    }

    /**
     * ReceiveWXinAccessToken constructor.
     */
    public function __construct()
    {
    }
}

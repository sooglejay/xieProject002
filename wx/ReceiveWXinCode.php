<?php

/**
 * Created by PhpStorm.
 * User: sooglejay
 * Date: 17/9/14
 * Time: 20:09
 */
class ReceiveWXinCode
{
    private function w($resArr)
    {
        file_put_contents('./test.txt', print_r($resArr, true));
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
}

new ReceiveWXinCode();
<?php

require_once dirname(__FILE__) . "/Cache.php";
require_once dirname(__FILE__) . "/Http.php";

/**
 * 微信类
 */
class Wechat
{

    private static $appid = "wxb76c5258ffa59386";
    private static $secret = "80b8d4e879263070071d9736d511f3b5";

    /**
     * Wechat constructor.
     */
    public function __construct()
    {
        $at = self::accessToken();
        print_r($at);
        $accessToken = json_encode($at);
        echo "\n\naccess_token=" . $accessToken;
    }

    /**
     * 获取 access_token
     * @return stdClass {"access_token":"ACCESS_TOKEN","expires_in":7200,"expires_timestamp":1479797789}
     */
    public static function accessToken()
    {
        $cache = Cache::get('access_token');
        if (empty($cache) || $cache->expires_timestamp < time()) {
            $response = Http::get('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . self::$appid . '&secret=' . self::$secret);
            $response->expires_timestamp = time() + ($response->expires_in - 30);
            Cache::put('access_token', $response);
            $data = $response;
        } else {
            $data = $cache;
        }
        return $data;
    }

}

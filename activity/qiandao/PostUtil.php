<?php
/**
 * Created by PhpStorm.
 * User: sooglejay
 * Date: 17/9/25
 * Time: 10:47
 */



class PostUtil{

    /**
     * PostUtil constructor.
     */
    public function __construct()
    {

        $curl = new Curl\Curl();
        $url = "https://dopen.weimob.com/fuwu/c/oauth2/token?code=A6FoOf&grant_type=authorization_code&client_id=F9427D8838E07BD463872C6C99574354&client_secret=48BF17DD2C5D551B8E67F7E362DF6FFB&redirect_uri=http://test.sighub.com/ziyan/wx/ReceiveWXinCode.php";
        $curl->post($url);
        $bb = $curl->response;
    }
}
$url = 'http://211.149.234.57:5818/Interface.aspx?Data='.$data;
//初始化
$ch = curl_init();
//设置选项，包括URL
curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 0);

//执行并获取HTML文档内容
$output = curl_exec($ch);

//释放curl句柄
curl_close($ch);

//打印获得的数据
print_r($output);
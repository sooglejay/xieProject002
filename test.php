<?php
///**
// * Created by PhpStorm.
// * User: sooglejay
// * Date: 17/9/1
// * Time: 21:56
// */
////date_default_timezone_set("PRC");

// create a new cURL resource
$ch = curl_init();

// set URL and other appropriate options
curl_setopt($ch, CURLOPT_URL, "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wxb76c5258ffa59386&secret=80b8d4e879263070071d9736d511f3b5");
curl_setopt($ch, CURLOPT_HEADER, 0);

// grab URL and pass it to the browser
$s = curl_exec($ch);

// close cURL resource, and free up system resources
curl_close($ch);
echo $s;
?>

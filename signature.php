<?php

require_once './jssdk.php';

$url = $_GET['url'];

$jssdk = new JSSDK('wxb76c5258ffa59386', '80b8d4e879263070071d9736d511f3b5');
$wxconfig = $jssdk->getSignPackage($url);

header('Access-Control-Allow-Origin: *');
echo json_encode($wxconfig);

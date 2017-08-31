<?php
$url = $_GET['url'];
$wxconfig = shell_exec("java -jar signature_jar.jar " . $url);
header('Access-Control-Allow-Origin: *');
echo json_encode($wxconfig);

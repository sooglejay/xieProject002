<?php
/**
 * Created by PhpStorm.
 * User: sooglejay
 * Date: 17/9/25
 * Time: 10:47
 */



$url = 'http://211.149.234.57:5818/Interface.aspx';
$ch = curl_init();
$timeout = 50000;
$arr = array("Data"=>'OENDODU1MDg2QzNFM0I2MzdERjY0NUQ5NTJGMkUwRTY2NUE2M0UxNTUzOEM1NjU0QzM5MkU2OTlBRTI0QUQzODlGQTVBNzdCQThEQTc0NUI4NjBCQzhCMTQ1QTJDNTEwQ0FDNjQwRTIxQjNDOEMwRTVGODU3NTRFOEQzOUUzM0M4RTU3NDY3QjlGREM3NkY0NjI0ODhFQkFBOUFGRDBENTYwMjJFMEMzMkFDRTE4MzlFRTRFODEwQkFBRTUzQzY2');
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
// post数据
curl_setopt($ch, CURLOPT_POST, 1);
// post的变量
curl_setopt($ch, CURLOPT_POSTFIELDS, $arr);

$output = curl_exec($ch);
curl_close($ch);

//打印获得的数据
print_r($output);


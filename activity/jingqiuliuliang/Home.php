<?php
/**
 * Created by PhpStorm.
 * User: sooglejay
 * Date: 17/11/5
 * Time: 22:15
 */
require_once 'Search.php';
$handler = new Search();
if (isset($_REQUEST['checkUser'])) {
    $mobileNumber = $_REQUEST['mobileNumber'];
    $type = $handler->checkTypeByPhone($mobileNumber);
    echo json_encode($type);
}
if (isset($_REQUEST['doBuy'])) {
    $mobileNumber = $_REQUEST['mobileNumber'];
    $type = $handler->doBuy($mobileNumber, '未填写地址');
    echo json_encode($type);
}
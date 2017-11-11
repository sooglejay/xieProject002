<?php
/**
 * Created by PhpStorm.
 * User: sooglejay
 * Date: 17/11/11
 * Time: 15:54
 */

if (isset($_REQUEST['openId'])) {
    $openId = $_REQUEST['openId'];
    header("HTTP/1.1 303 See Other");
    header("Location: http://test.sighub.com/ziyan/shop/html?openId=$openId");
    exit;
}

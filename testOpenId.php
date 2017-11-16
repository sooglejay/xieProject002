<?php
ini_set('date.timezone', 'Asia/Shanghai');

/**
 * Created by PhpStorm.
 * User: sooglejay
 * Date: 17/8/27
 * Time: 09:59
 */
require_once dirname(__FILE__) . "/bootstrap.php";
require_once dirname(__FILE__) . "/model/User.php";
require_once dirname(__FILE__) . "/model/Shop.php";
//require_once dirname(__FILE__) . "/wx/ReceiveWXinCode.php";

class testOpenId extends App
{

    /**
     * Login constructor.
     */
    public function __construct()
    {
        parent::__construct();
        echo "come ...";
//        echo json_decode($_SESSION, true);
    }
}

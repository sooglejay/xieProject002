<?php
namespace End_2017;

use App;

require_once dirname(__FILE__) . "/../../../bootstrap.php";
require_once dirname(__FILE__) . "/../model/User.php";
require_once dirname(__FILE__) . "/../model/AllPhoneSegments.php";
require_once dirname(__FILE__) . "/../model/UserType.php";

/**
 * Created by PhpStorm.
 * User: sooglejay
 * Date: 17/11/16
 * Time: 13:04
 */
class MainApp extends App
{


    /**
     * MainApp constructor.
     */
    public function __construct()
    {
        parent::__construct();
        if (!isset($_POST["phoneNumber"])) {
            echo json_encode(array("code" => 201, "message" => "请输入手机号码"));
        }
        $phoneNumber = $_POST["phoneNumber"];
        $phoneSeg = substr($phoneNumber,0,7);
        $allPhoneRepo = $this->entityManager->getRepository("AllPhoneSegments");
        if ($allPhoneRepo instanceof End2017AllPhoneSegmentsRepository) {
            if($allPhoneRepo->checkExist($phoneSeg)){
                //是目标用户

            }else{
                //不是目标用户
            }
        }
    }
}
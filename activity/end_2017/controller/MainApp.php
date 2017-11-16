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
        echo $this->checkPhoneNumber($phoneNumber);
    }

    private function checkPhoneNumber($phoneNumber)
    {
        $phoneSeg = substr($phoneNumber, 0, 7);
        $allPhoneRepo = $this->entityManager->getRepository("AllPhoneSegments");
        if ($allPhoneRepo instanceof End2017AllPhoneSegmentsRepository) {
            if ($allPhoneRepo->checkExist($phoneSeg)) {
                //是目标用户
                $userRepo = $this->entityManager->getRepository("User");
                if ($userRepo instanceof End2017UserRepository) {
                    // 第一类和第二类用户
                    $userType = $userRepo->getTypeByPhoneNumber($phoneNumber);
                    if (!is_null($userType)) {
                        $type = $userType->getTypeVal();
                        if ($type == 1) {
                            $typeName = "one";
                        } else if ($type == 2) {
                            $typeName = "two";
                        } else {
                            $typeName = "error";
                        }
                        return json_encode(array("code" => 200, "type" => $typeName));
                    } else {
                        return json_encode(array("code" => 201, "message" => "找不到符合要求的活动类型"));
                    }
                }
                // 第三类用户
                return json_encode(array("code" => 200, "type" => "three"));
            }
        }
        return json_encode(array("code" => 201, "message" => "对不起\n您输入的号码不满足本次活动要求"));
    }
}
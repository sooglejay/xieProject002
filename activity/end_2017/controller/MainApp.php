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
                    $userType = $userRepo->getTypeByPhoneNumber($phoneNumber);
                    if (!is_null($userType)) {
                        $type_one = "one";
                        $type_two = "two";
                        $type_three = "three";
                        $type = $userType->getTypeVal();
                        $typeName = 0;
                        switch ($type) {
                            case 1:
                                $typeName = $type_one;
                                break;
                            case 2:
                                $typeName = $type_two;
                                break;
                            case 3:
                                $typeName = $type_three;
                                break;
                        }
                        return json_encode(array("code" => 200, "type" => $typeName));
                    } else {
                        return json_encode(array("code" => 201, "message" => "找不到符合要求的活动类型"));
                    }
                }

            }
        }
        return json_encode(array("code" => 201, "message" => "对不起\n您输入的号码不满足本次活动要求"));
    }
}
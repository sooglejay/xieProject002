<?php
namespace End_2017;

use App;

require_once dirname(__FILE__) . '/../../../bootstrap.php';
require_once dirname(__FILE__) . '/../model/User.php';
require_once dirname(__FILE__) . '/../model/AllPhoneSegments.php';
require_once dirname(__FILE__) . '/../model/Order.php';
require_once dirname(__FILE__) . '/../model/UserType.php';
require_once dirname(__FILE__) . '/../model/ActivityType.php';

/**
 * Created by PhpStorm.
 * User: sooglejay
 * Date: 17/11/16
 * Time: 13:04
 */
class CheckUserApp extends App
{
    /**
     * MainApp constructor.
     */
    public function __construct()
    {
        parent::__construct();
        if (!isset($_POST['phoneNumber'])) {
            echo json_encode(array('code' => 201, 'message' => '请输入手机号码'));
            return;
        }
        $phoneNumber = $_POST['phoneNumber'];
        echo $this->checkPhoneNumber($phoneNumber);
    }

    private function checkPhoneNumber($phoneNumber)
    {
        $phoneSeg = substr($phoneNumber, 0, 7);
        $allPhoneRepo = $this->entityManager->getRepository('End_2017\AllPhoneSegments');
        if ($allPhoneRepo instanceof End2017AllPhoneSegmentsRepository) {
            if ($allPhoneRepo->checkExist($phoneSeg)) {
                //是目标用户
                $userRepo = $this->entityManager->getRepository('End_2017\User');
                if ($userRepo instanceof End2017UserRepository) {
                    $userType = $userRepo->getTypeByPhoneNumber($phoneNumber);
                    // 第一类和第二类用户
                    if ($userType instanceof UserType) {
                        $type = $userType->getTypeVal();
                        if ($type == 1) {
                            $typeName = 'ao_60_ne';
                        } else if ($type == 2) {
                            $typeName = 'tb_xyz_wo';
                        } else if ($type == 3) {
                            $typeName = 'th_c_ree';
                        } else {
                            $typeName = 'error';
                        }
                        return json_encode(array('code' => 200, 'type' => $typeName));
                    }
                }
                // 第三类用户
                return json_encode(array('code' => 200, 'type' => 'th_c_ree'));
            }
        }
        return json_encode(array('code' => 201, 'message' => '对不起<br/>您输入的号码不满足本次活动要求'));
    }
}

new CheckUserApp();
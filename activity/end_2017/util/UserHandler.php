<?php
/**
 * Created by PhpStorm.
 * User: sooglejay
 * Date: 17/11/16
 * Time: 16:16
 */

namespace End_2017;

use App;

require_once dirname(__FILE__) . "/../../../bootstrap.php";
require_once dirname(__FILE__) . "/../model/User.php";
require_once dirname(__FILE__) . "/../model/AllPhoneSegments.php";
require_once dirname(__FILE__) . "/../model/UserType.php";

class UserHandler extends App
{


    /**
     * UserHandler constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->initUserType();
    }

    public function initUserType()
    {
        $userTypeRepo = $this->entityManager->getRepository('End_2017\UserType');
        $activityTypeRepo = $this->entityManager->getRepository('End_2017\ActivityType');
        if ($userTypeRepo instanceof End2017UserTypeRepository) {

            if ($activityTypeRepo instanceof End2017ActivityTypeRepository) {
                $userTypeRepo->addUserType("目标用户一", 1);
                $userTypeRepo->addUserType("目标用户二", 2);
                $userTypeRepo->addUserType("目标用户三", 3);

                $activityTypeRepo->saveActivityType(1, 'DLLZS', '超低流量客户免费送');
                $activityTypeRepo->saveActivityType(2, 'LLCX5', '低流量客户优惠月促销包');
                $activityTypeRepo->saveActivityType(2, 'LLBN20', '低流量客户优惠半年促销包');
                $activityTypeRepo->saveActivityType(3, 'LLZS20', '15元1.5G');
                $activityTypeRepo->saveActivityType(3, 'LLZS30', '30元2G+30G视频流量');
                $activityTypeRepo->saveActivityType(3, 'LLJB50', '50元6G');
            }
        }
    }

}

new UserHandler();
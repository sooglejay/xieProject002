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
        $userTypeRepo = $this->entityManager->getRepository("End_2017\\UserType");
        if ($userTypeRepo instanceof End2017UserTypeRepository) {
            $userTypeRepo->addUserType("目标用户一", 1);
            $userTypeRepo->addUserType("目标用户二", 2);
            $userTypeRepo->addUserType("目标用户三", 3);
        }
    }

}

new UserHandler();
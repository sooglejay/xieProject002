<?php
/**
 * Created by PhpStorm.
 * User: sooglejay
 * Date: 17/11/16
 * Time: 16:16
 */

namespace End_2017;

use App;
use Exception;

require_once dirname(__FILE__) . '/../../../bootstrap.php';
require_once dirname(__FILE__) . '/../model/User.php';
require_once dirname(__FILE__) . '/../model/AllPhoneSegments.php';
require_once dirname(__FILE__) . '/../model/Order.php';
require_once dirname(__FILE__) . '/../model/UserType.php';

class OrderApp extends App
{


    /**
     * OrderApp constructor.
     */
    public function __construct()
    {
        parent::__construct();

        if (!isset($_GET['phoneNumber'])) {
            return;
        }
        if (!isset($_GET['activityCode'])) {
            return;
        }
        if (!isset($_GET['type'])) {
            return;
        }
        $type = $_GET['type'];
        $activityCode = $_GET['activityCode'];
        $phoneNumber = $_GET['phoneNumber'];
        if ($type == 1 || $type == 2) {
            $this->handlerTypeOneTwo($activityCode, $phoneNumber);
        } else if ($type == 3) {
            $this->handlerTypeThree($activityCode, $phoneNumber);
        } else {
            echo json_encode(array("message" => "对不起，没有这个活动！", "code" => 201));
            return;
        }
    }

    private function handlerTypeOneTwo($aCode, $phoneNumber)
    {
        $result = array();
        $result['code'] = 201;
        $result['message'] = '其他错误：类型不合法';
        try {
            $userRepo = $this->entityManager->getRepository('End_2017\User');
            if ($userRepo instanceof End2017UserRepository) {
                $userEntity = $userRepo->findOneBy(array('phoneNumber' => $phoneNumber));
                if ($userEntity instanceof User) {
                    $orderRepo = $this->entityManager->getRepository('End_2017\Order');
                    $activityRepo = $this->entityManager->getRepository('End_2017\ActivityType');
                    if ($activityRepo instanceof End2017ActivityTypeRepository) {
                        $activityEntity = $activityRepo->getEntityByActivityCode($aCode);
                        if ($activityEntity instanceof ActivityType) {
                            if ($orderRepo instanceof End2017OrderRepository) {
                                return $orderRepo->saveOrder($userEntity, $activityEntity);
                            }
                        }
                    }
                } else {
                    $result['message'] = '对不起，您的号码无法参加此活动！';
                }
            }
        } catch (Exception $exp) {
            $result['message'] = $exp->getMessage();
        }
        return $result;
    }

    private function handlerTypeThree($activityCode, $phoneNumber)
    {
        $result = array();
        $result['code'] = 201;
        $result['message'] = '其他错误：类型不合法';
        $orderRepo = $this->entityManager->getRepository('End_2017\Order');
        if ($orderRepo instanceof End2017OrderRepository) {
            $userTypeRepo = $this->entityManager->getRepository('End_2017\UserType');
            $userTypeEntity = $userTypeRepo->findOneBy(array('typeVal' => 3));
            if ($userTypeEntity instanceof UserType) {
                try {
                    $userRepo = $this->entityManager->getRepository('End_2017\User');
                    if ($userRepo instanceof End2017UserRepository) {
                        $userEntity = $userRepo->findOneBy(array('phoneNumber' => $phoneNumber));
                        if ($userEntity instanceof User) {
                            // 说明这个家伙曾经来过
                        } else {
                            // 这个家伙第一次来
                            $userEntity = new User();
                            $userEntity->setUserType($userTypeEntity);
                            $userEntity->setPhoneNumber($phoneNumber);
                            $this->entityManager->persist($userEntity);
                        }
                        $activityRepo = $this->entityManager->getRepository('End_2017\ActivityType');
                        if ($activityRepo instanceof End2017ActivityTypeRepository) {
                            $activityEntity = $activityRepo->getEntityByActivityCode($activityCode);
                            if ($activityEntity instanceof ActivityType) {
                                if ($orderRepo instanceof End2017OrderRepository) {
                                    return $orderRepo->saveOrder($userEntity, $activityEntity);
                                }
                            }
                        }
                    }
                } catch (Exception $exception) {
                    $result['message'] = $exception->getMessage();
                }
            }
        }
        return $result;
    }
}

new OrderApp();
<?php
namespace BaZhong;

use App;

require_once dirname(__FILE__) . '/../../../../bootstrap.php';
require_once dirname(__FILE__) . '/../model/User.php';
require_once dirname(__FILE__) . '/../model/Sign.php';
require_once dirname(__FILE__) . '/../model/AllPhoneSegments.php';

/**
 * Created by PhpStorm.
 * User: sooglejay
 * Date: 17/11/16
 * Time: 13:04
 */
class SignApp extends App
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
        echo json_encode($this->checkPhoneNumber($phoneNumber));
    }

    private function checkPhoneNumber($phoneNumber)
    {
        $phoneSeg = substr($phoneNumber, 0, 7);
        $allPhoneRepo = $this->entityManager->getRepository('BaZhong\AllPhoneSegments');
        $signRepo = $this->entityManager->getRepository('BaZhong\Sign');
        if ($allPhoneRepo instanceof BZAllPhoneSegmentsRepository) {
            if ($allPhoneRepo->checkExist($phoneSeg)) {
                $orders = $signRepo->findBy(array('phoneNumber' => $phoneNumber), array('time' => 'DESC'));
                foreach ($orders as $order) {
                    if (count($orders) > 4) {
                        return array('code' => 200, 'count' => 6);
                    }
                    if ($order instanceof Sign) {
                        $lastSignDate = $order->getTime();
                        $lTS = $lastSignDate->getTimestamp();
                        $now = new \DateTime("now");
                        $nTS = $now->getTimestamp();
                        $days = round(($nTS - $lTS) / 3600 / 24);
                        if ($days >= 1) {
                            $c = count($orders);
                            if ($c <= 4) {
                                $this->saveSign($phoneNumber);
                                return array('code' => 200, 'count' => ($c + 1));
                            } else {
                                return array('code' => 200, 'count' => 6);
                            }
                        } else {
                            return array('code' => 201, 'message' => '一天之内不能重复签到！');
                        }
                    } else {
                        return array('code' => 503, 'message' => '对不起<br/>类型异常，请重试');
                    }
                }
            } else {
                return array('code' => 201, 'message' => '对不起<br/>您输入的号码不满足本次活动要求');
            }
        }
        return array('code' => 503, 'message' => '对不起<br/>遇到系统异常，请重试');
    }

    private function saveSign($phoneNumber)
    {
    }
}

new CheckUserApp();
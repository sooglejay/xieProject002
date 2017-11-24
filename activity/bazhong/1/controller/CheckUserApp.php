<?php
namespace BaZhong;

use App;

require_once dirname(__FILE__) . '/../../../../bootstrap.php';
require_once dirname(__FILE__) . '/../model/User.php';
require_once dirname(__FILE__) . '/../model/AllPhoneSegments.php';

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
        echo json_encode($this->checkPhoneNumber($phoneNumber));
    }

    private function checkPhoneNumber($phoneNumber)
    {
        $phoneSeg = substr($phoneNumber, 0, 7);
        $allPhoneRepo = $this->entityManager->getRepository('BaZhong\AllPhoneSegments');
        if ($allPhoneRepo instanceof BZAllPhoneSegmentsRepository) {
            if ($allPhoneRepo->checkExist($phoneSeg)) {
                //是目标用户
                return array('code' => 200, 'message' => '登录成功！');

            }
        }
        return array('code' => 201, 'message' => '对不起<br/>您输入的号码不满足本次活动要求');
    }
}

new CheckUserApp();
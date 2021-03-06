<?php
ini_set('display_errors', 1);
ini_set('date.timezone', 'Asia/Shanghai');

/**
 * Created by PhpStorm.
 * User: sooglejay
 * Date: 17/8/23
 * Time: 21:27
 */
require_once dirname(__FILE__) . "/../../bootstrap.php";
require_once dirname(__FILE__) . "/../../model/User.php";
require_once dirname(__FILE__) . "/../../model/Shop.php";

class IndexApp extends App
{
    public function __construct()
    {
        parent::__construct();
        if ($this->isLogin()) {
            echo json_encode(array("code" => 200, "data" => $this->doShowPage()));
        } else {
            echo json_encode(array("code" => 201, "message" => "您未登录，请重新进入公众号点击图文消息！", "error" => "error"));
        }
    }

    private function doShowPage()
    {
        $openId = $_POST['openId'];
        if (!$this->checkOpenIdValidation($openId)) {
            return array("code" => 201, "message" => "openId 不合法，请重新进入公众号点击图文消息！", "error" => "error");
        }
        $userRepo = $this->entityManager->getRepository('User');
        $shopRepo = $this->entityManager->getRepository('Shop');
        $userEntity = $userRepo->findOneBy(array("openId" => $openId));
        if (is_null($userEntity) || !($userEntity instanceof User)) {
            return array("code" => 201, "message" => "未能获取用户信息，请重新登录", "error" => "error");
        }
        $userName = $userEntity->getAccountName();
        if ($userName == "88888888") {
            $allShopsFromAll = $shopRepo->findAll();
            $userEntity->setShopNum(count($allShopsFromAll));
        } else {
            switch ($userName) {
                case "yanjiang":
                    $county = "雁江";
                    break;
                case "anyue":
                    $county = "安岳";
                    break;
                case "lezhi":
                    $county = "乐至";
                    break;
                default:
                    $county = -1;
                    break;
            }
            if ($county != -1) {
                $num = 0;
                $users = $userRepo->findBy(array("county" => $county));
                foreach ($users as $u) {
                    if ($u instanceof User) {
                        $num += $u->getShopNum();
                    }
                }
                $userEntity->setShopNum($num);
            }
        }
        return $userEntity->toArray();
    }
}

new IndexApp();
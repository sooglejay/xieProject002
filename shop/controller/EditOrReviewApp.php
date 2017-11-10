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
class EditOrReviewApp extends App
{
    public function __construct()
    {
        parent::__construct();
        if ($this->isLogin()) {
            echo json_encode($this->doShowPage());
        } else {
            echo json_encode(array("code" => 201, "message" => "请重新登录", "error" => "error"));
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
            return array("code" => 201, "message" => "未能获取用户信息，请重新进入公众号点击图文消息！", "error" => "error");
        }

        $id = $_POST['id'];
        $shopEntity = $shopRepo->find($id);
        if ($shopEntity instanceof Shop) {
            return $shopEntity->toArray();
        }
        return array("code" => 201, "message" => "您所查看的商铺不存在！", "error" => "error");
    }
}

new EditOrReviewApp();
<?php

ini_set('date.timezone', 'Asia/Shanghai');

/**
 * Created by PhpStorm.
 * User: sooglejay
 * Date: 17/8/27
 * Time: 09:59
 */
require_once dirname(__FILE__) . "/../../bootstrap.php";
require_once dirname(__FILE__) . "/../../model/User.php";
require_once dirname(__FILE__) . "/../../model/Shop.php";

class SaveApp extends App
{
    /**
     * Login constructor.
     */
    public function __construct()
    {
        parent::__construct();
        if (!$this->isLogin()) {
            echo json_encode(array("code" => 201, "message" => "请重新登录", "error" => "error"));
            return;
        }
        if (isset($_POST['action'])) {
            $action = $_POST['action'];
            if ($action == "save_shop") {
                echo json_encode($this->actionSaveShop());
            } else if ($action == "edit_save_shop") {
                echo json_encode($this->actionEditShop());
            }
        }
    }

    private
    function actionSaveShop()
    {
        $openId = $_POST['openId'];
        if (!$this->checkOpenIdValidation($openId)) {
            return array("code" => 201, "message" => "openId 不合法，请重新进入公众号点击图文消息！", "error" => "error");
        }
        $shopRepo = $this->entityManager->getRepository('Shop');
        $userRepo = $this->entityManager->getRepository('User');
        $userEntity = $userRepo->findOneBy(array("openId" => $openId));
        if (is_null($userEntity) || !($userEntity instanceof User)) {
            return array("code" => 201, "message" => "未能获取用户信息，请重新登录", "error" => "error");
        }
        $shopName = $_POST['shop_name'];
        $shops = $shopRepo->findOneBy(array("shop_name" => $shopName));

        foreach ($shops as $addedShop) {
            return array("code" => 201, "message" => $shopName . "店铺名已经存在，请重新输入！", "error" => "error");
        }
        try {
            $shopLng = isset($_POST['shop_lng']) ? $_POST['shop_lng'] . "" : "";
            $shopLat = isset($_POST['shop_lat']) ? $_POST['shop_lat'] . "" : "";
            $shop209 = isset($_POST['shop_209']) ? $_POST['shop_209'] : "";
            $shop280 = isset($_POST['shop_280']) ? $_POST['shop_280'] : "";
            $shopName = isset($_POST['shop_name']) ? $_POST['shop_name'] : "";
            $shopLandLine = isset($_POST['shop_landline']) ? $_POST['shop_landline'] : "";
            $shopAddress = isset($_POST['shop_addr']) ? $_POST['shop_addr'] : "";
            $shopType = isset($_POST['shop_type']) ? $_POST['shop_type'] : "";
            $shopMemNum = isset($_POST['shop_mem_num']) ? $_POST['shop_mem_num'] : "";
            $shopOperator = isset($_POST['shop_operator']) ? $_POST['shop_operator'] : "";
            $shopContact2 = isset($_POST['shop_contact2']) ? $_POST['shop_contact2'] : "";
            $shopContact1 = isset($_POST['shop_contact1']) ? $_POST['shop_contact1'] : "";
            $shopGroupNet = isset($_POST['shop_group_net']) ? $_POST['shop_group_net'] : "";
            $shopStreet = isset($_POST['shop_street']) ? $_POST['shop_street'] : "";
            $shopBroadbandCover = isset($_POST['shop_broadband_cover']) ? $_POST['shop_broadband_cover'] : "";

            $sh = new Shop();
            $sh->setTime(date("Y-m-d H:i:s"));
            $sh->setShopUser($userEntity);
            $sh->setShop209($shop209);
            $sh->setShopName($shopName);
            $sh->setShopLandline($shopLandLine);
            $sh->setShop280($shop280);
            $sh->setShopAddr($shopAddress);
            $sh->setShopBroadbandCover($shopBroadbandCover);
            $sh->setShopContact1($shopContact1);
            $sh->setShopContact2($shopContact2);
            $sh->setShopMemNum($shopMemNum);
            $sh->setShopGroupNet($shopGroupNet);
            $sh->setShopOperator($shopOperator);
            $sh->setShopStreet($shopStreet);
            $sh->setShopType($shopType);
            $sh->setShopLng($shopLng);
            $sh->setShopLat($shopLat);
            $code = '';
            for ($i = 0; $i < 7; $i++) {
                $code .= range('a', 'z')[random_int(0, 25)];
            }
            $sh->setShopUniqueCode($code);
            $this->entityManager->persist($sh);
            $this->entityManager->persist($userEntity);
            $this->entityManager->flush();
            return array("message" => "添加成功!", "openId" => $_SESSION['openId']);
        } catch (Exception $e) {
            return array("message" => $e->getMessage(), "error" => "error");
        }
    }


    private
    function actionEditShop()
    {
        $openId = $_POST['openId'];
        if (!$this->checkOpenIdValidation($openId)) {
            return array("code" => 201, "message" => "openId 不合法，请重新进入公众号点击图文消息！", "error" => "error");
        }
        $shopRepo = $this->entityManager->getRepository('Shop');
        $userRepo = $this->entityManager->getRepository('User');
        $userEntity = $userRepo->findOneBy(array("openId" => $openId));
        if (is_null($userEntity) || !($userEntity instanceof User)) {
            return array("code" => 201, "message" => "未能获取用户信息，请重新登录", "error" => "error");
        }

        $id = $_POST['id'];
        try {
            $shopLng = isset($_POST['shop_lng']) ? $_POST['shop_lng'] . "" : "";
            $shopLat = isset($_POST['shop_lat']) ? $_POST['shop_lat'] . "" : "";
            $shop209 = isset($_POST['shop_209']) ? $_POST['shop_209'] : "";
            $shop280 = isset($_POST['shop_280']) ? $_POST['shop_280'] : "";
            $shopName = isset($_POST['shop_name']) ? $_POST['shop_name'] : "";
            $shopLandLine = isset($_POST['shop_landline']) ? $_POST['shop_landline'] : "";
            $shopAddress = isset($_POST['shop_addr']) ? $_POST['shop_addr'] : "";
            $shopType = isset($_POST['shop_type']) ? $_POST['shop_type'] : "";
            $shopMemNum = isset($_POST['shop_mem_num']) ? $_POST['shop_mem_num'] : "";
            $shopOperator = isset($_POST['shop_operator']) ? $_POST['shop_operator'] : "";
            $shopContact2 = isset($_POST['shop_contact2']) ? $_POST['shop_contact2'] : "";
            $shopContact1 = isset($_POST['shop_contact1']) ? $_POST['shop_contact1'] : "";
            $shopGroupNet = isset($_POST['shop_group_net']) ? $_POST['shop_group_net'] : "";
            $shopStreet = isset($_POST['shop_street']) ? $_POST['shop_street'] : "";
            $shopBroadbandCover = isset($_POST['shop_broadband_cover']) ? $_POST['shop_broadband_cover'] : "";

            $shopEntity = $shopRepo->find($id);
            $shopEntity->setShop209($shop209);
            $shopEntity->setShopName($shopName);
            $shopEntity->setShopLandline($shopLandLine);
            $shopEntity->setShop280($shop280);
            $shopEntity->setShopAddr($shopAddress);
            $shopEntity->setShopBroadbandCover($shopBroadbandCover);
            $shopEntity->setShopContact1($shopContact1);
            $shopEntity->setShopContact2($shopContact2);
            $shopEntity->setShopMemNum($shopMemNum);
            $shopEntity->setShopGroupNet($shopGroupNet);
            $shopEntity->setShopOperator($shopOperator);
            $shopEntity->setShopStreet($shopStreet);
            $shopEntity->setShopType($shopType);
            $shopEntity->setShopLng($shopLng);
            $shopEntity->setShopLat($shopLat);
            $this->entityManager->persist($shopEntity);
            $this->entityManager->flush();
            return array("code" => 200, "message" => "修改成功！");
        } catch (Exception $e) {
            return array("code" => 200, "message" => "保存失败," . $e->getMessage(), "error" => "error");
        }
    }
}

new SaveApp();
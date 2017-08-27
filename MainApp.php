<?php

/**
 * Created by PhpStorm.
 * User: sooglejay
 * Date: 17/8/23
 * Time: 21:27
 */
require_once "bootstrap.php";
require_once "model/User.php";
require_once "model/Shop.php";

class MainApp extends App
{
    public static $INDEX = "index";
    public static $SEARCH = "search";
    public static $SAVE_SHOP = "save_shop";

    private $userRepo;
    private $shopRepo;
    private $loginUserName;
    private $loginUserId;
    private $loginUserModel;

    /**
     * MainApp constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->userRepo = $this->entityManager->getRepository('User');
        $this->shopRepo = $this->entityManager->getRepository('Shop');
        if (isset($_SESSION) && isset($_SESSION['userName'])) {
            $this->loginUserName = $_SESSION['userName'];
            $this->loginUserId = $_SESSION['userId'];
            $this->loginUserModel = $this->userRepo->find($this->loginUserId);
        } else {
            $this->returnWithMessage("请先登录", "error");
            return;
        }
        $actionName = $_REQUEST['action'];
        if ($actionName == MainApp::$INDEX) {
            $userModels = $this->userRepo->findAll();
            if ($this->loginUserName != null) {
                foreach ($userModels as $userModel) {
                    if ($userModel instanceof User && $userModel->getAccountName() == $this->loginUserName) {
                        echo json_encode($userModel->toArray());
                    }
                }
            } else if (count($userModels) > 0) {
                $model = $userModels[0];
                if ($model instanceof User) {
                    echo json_encode($model->toArray());
                }
            }

        } else if ($actionName == MainApp::$SEARCH) {
            $retArr = [];
            $keyWord = $_REQUEST['keyWord'];
        } else if ($actionName == MainApp::$SAVE_SHOP) {
            if (is_null($this->loginUserId)) {
                $this->returnWithMessage("请先登录再完成操作", "请先登录再完成操作");
                return;
            }
            $this->actionSaveShop();
        }
    }

    private function returnWithMessage($messageStr, $errorStr = null)
    {
        $ret = array("message" => $messageStr);
        if (isset($errorStr)) {
            $ret["error"] = $errorStr;
        }
        echo json_encode($ret);
    }

    private function actionSaveShop()
    {

        $shopName = $_REQUEST['shop_name'];
        $shops = $this->shopRepo->findAll();
        foreach ($shops as $addedShop) {
            if ($addedShop instanceof Shop) {
                if ($addedShop->getShopName() == $shopName) {
                    echo json_encode(array("message" => "店铺名已经存在，请重新输入！", "error" => "error"));
                    return;
                }
            }
        }
        $shop = new Shop();
        $resFlag = true;
        $retArr = array();

        try {
            $shop->setShopName($_POST['shop_name']);
            $shop->setShopAddr($_POST['shop_addr']);
            $shop->setShopStreet($_POST['shop_street']);
            $shop->setShopContact1($_POST['shop_contact1']);
            $shop->setShopContact2($_POST['shop_contact2']);
            $shop->setShopType($_POST['shop_type']);
            $shop->setShop280($_POST['shop_280']);
            $shop->setShopGroupNet($_POST['shop_group_net']);
            $shop->setShopMemNum(isset($_REQUEST['shop_mem_num']) ? $_REQUEST['shop_mem_num'] : "");
            $shop->setShop209(isset($_REQUEST['shop_209']) ? $_REQUEST['shop_209'] : "");
            $shop->setShopBroadbandCover(isset($_REQUEST['shop_broadband_cover']) ? $_REQUEST['shop_broadband_cover'] : "");
            $shop->setShopLandline(isset($_REQUEST['shop_landline']) ? $_REQUEST['shop_landline'] : "");
            $shop->setShopOperator(isset($_REQUEST['shop_operator']) ? $_REQUEST['shop_operator'] : "");
            $shop->setShopUser($this->loginUserModel);
            $this->entityManager->persist($shop);
            $this->entityManager->flush();
        } catch (Exception $e) {
            $resFlag = false;
            $retArr = array("message" => "添加失败!", "error" => $e);
        }
        if ($resFlag) {
            $retArr = array("message" => "添加成功!");
        }
        echo json_encode($retArr);
    }
}

new MainApp();

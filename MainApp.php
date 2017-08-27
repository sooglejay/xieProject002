<?php

/**
 * Created by PhpStorm.
 * User: sooglejay
 * Date: 17/8/23
 * Time: 21:27
 */
require_once "bootstrap.php";
require_once "model/User.php";

class MainApp extends App
{
    public static $INDEX = "index";
    public static $SEARCH = "search";
    public static $SAVE_SHOP = "save_shop";

    private $userRepo;
    private $shopRepo;
    private $loginUserName;
    private $loginUserId;

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
        $shopName = $_POST['shop_name'];
        $shops = $this->shopRepo->findAll();
        foreach ($shops as $addedShop) {
            if ($addedShop instanceof Shop) {
                if ($addedShop->getShopName() == $shopName) {
                    echo json_encode(array("message" => "店铺名已经存在，请重新输入！", "error" => "error"));
                    return;
                }
            }
        }
        $userObj = $this->userRepo->find($this->loginUserId);
        try {
            $shopRep = $this->entityManager->getRepository("Shop");
            if ($shopRep instanceof ShopRepository) {
                $shopRep->addShop($shopRep->getShopArrayFromRequest($_REQUEST), $userObj);
            } else {
                die("jiangwei");
            }
        } catch (Exception $e) {
            die($e->getMessage());
        }
        echo json_encode(array("message" => "添加成功!"));
    }
}

new MainApp();


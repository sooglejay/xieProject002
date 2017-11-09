<?php

ini_set('display_errors', 1);
ini_set('date.timezone', 'Asia/Shanghai');

/**
 * Created by PhpStorm.
 * User: sooglejay
 * Date: 17/8/23
 * Time: 21:27
 */
require_once dirname(__FILE__) . "/bootstrap.php";
require_once dirname(__FILE__) . "/model/User.php";

class MainApp extends App
{
    public static $INDEX = "index";
    public static $SEARCH = "search";
    public static $SAVE_SHOP = "save_shop";
    public static $EDIT = "edit";
    public static $EDIT_SAVE = "edit_save_shop";
    public static $VIEW = "view";

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
            echo json_encode(array("message" => "请重新登录", "error" => "error"));
            return;
        }
        $actionName = $_REQUEST['action'];
        if ($actionName == MainApp::$INDEX) {
            $userModel = $this->userRepo->findOneBy(array("account_name" => $this->loginUserName));
            if ($this->loginUserName == "88888888") {
                $allShopsFromAll = $this->shopRepo->findAll();
                if ($userModel instanceof User) $userModel->setShopNum(count($allShopsFromAll));
            } else if ($this->loginUserName == "yanjiang") {

                $users = $this->userRepo->findBy(array("county" => "雁江"));
                $num = 0;
                foreach ($users as $u) {
                    if ($u instanceof User) {
                        $num += $u->getShopNum();
                    }
                }
                if ($userModel instanceof User) $userModel->setShopNum($num);

            } else if ($this->loginUserName == "anyue") {
                $users = $this->userRepo->findBy(array("county" => "安岳"));
                $num = 0;
                foreach ($users as $u) {
                    if ($u instanceof User) {
                        $num += $u->getShopNum();
                    }
                }
                if ($userModel instanceof User) $userModel->setShopNum($num);
            } else if ($this->loginUserName == "lezhi") {
                $users = $this->userRepo->findBy(array("county" => "乐至"));
                $num = 0;
                foreach ($users as $u) {
                    if ($u instanceof User) {
                        $num += $u->getShopNum();
                    }
                }
                if ($userModel instanceof User) $userModel->setShopNum($num);
            }
            if ($userModel instanceof User) {
                echo json_encode($userModel->toArray());
            }
        } else if ($actionName == MainApp::$SEARCH) {
            $keyWord = $_REQUEST['search'];
            $searchResultArr = $this->searchKeyWord($keyWord);
            $adminUser = $this->userRepo->findOneBy(array("account_name" => $this->loginUserName));
            $retArr = array();
            if ($this->loginUserName == "88888888") {
                if ($adminUser instanceof User) {
                    foreach ($searchResultArr as $shop) {
                        $shop["owner"] = ($shop["shopUser_id"] == $adminUser->getId());
                        $retArr[] = $shop;
                    }
                }
            } else {
                $ar = array("yanjiang" => "雁江", "anyue" => "安岳", "lezhi" => "乐至");
                if (isset($ar[$this->loginUserName])) {
                    foreach ($searchResultArr as $shop) {
                        $user = $this->userRepo->find($shop["shopUser_id"]);
                        if ($user instanceof User && $user->getCounty() == $ar[$this->loginUserName])
                            $retArr[] = $shop;
                    }
                } else {
                    if ($adminUser instanceof User)
                        foreach ($searchResultArr as $shop) {
                            if ($adminUser->getId() == $shop["shopUser_id"]) {
                                $retArr[] = $shop;
                            }
                        }
                }
            }
            echo json_encode($retArr);
        } else
            if ($actionName == MainApp::$SAVE_SHOP) {
                $this->actionSaveShop();
            } else if ($actionName == MainApp::$EDIT || $actionName == MainApp::$VIEW) {
                $id = $_REQUEST['id'];
                $shopEntity = $this->shopRepo->find($id);
                if ($shopEntity instanceof Shop) {
                    echo json_encode($shopEntity->toArray());
                }
            } else if ($actionName == MainApp::$EDIT_SAVE) {
                $this->actionEditShop($_REQUEST['id']);
            }
    }

    private
    function actionSaveShop()
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
            $sh->setShopUser($userObj);
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
            $this->entityManager->flush($sh);
            $ret = true;
        } catch (Exception $e) {
            $ret = false;
            echo json_encode(array("message" => $e->getMessage(), "error" => "error"));
        }
        if ($ret) {
            echo json_encode(array("message" => "添加成功!"));
        }
    }

//    private
//    function testSaveShop()
//    {
//
//        $shopName = $_POST['shop_name'] = "安岳测试2";
//        $_POST['shop_lng'] = '1234.233435454';
//        $_POST['shop_lat'] = '12.2324343';
//        $_POST['shop_209'] = '12.2324343';
//        $_POST['shop_contact1'] = '13678054215';
//        $_POST['shop_mem_num'] = '23';
//        $_POST['shop_broadband_cover'] = '是';
//        $this->shopRepo = $this->entityManager->getRepository("Shop");
//        $this->userRepo = $this->entityManager->getRepository("User");
//
//        $shops = $this->shopRepo->findAll();
//        foreach ($shops as $addedShop) {
//            if ($addedShop instanceof Shop) {
//                if ($addedShop->getShopName() == $shopName) {
//                    echo json_encode(array("message" => "店铺名已经存在，请重新输入！", "error" => "error"));
//                    return;
//                }
//            }
//        }
//        $userObj = $this->userRepo->find(1);
//        try {
//            $shopLng = isset($_POST['shop_lng']) ? $_POST['shop_lng'] . "" : "";
//            $shopLat = isset($_POST['shop_lat']) ? $_POST['shop_lat'] . "" : "";
//            $shop209 = isset($_POST['shop_209']) ? $_POST['shop_209'] : "";
//            $shop280 = isset($_POST['shop_280']) ? $_POST['shop_280'] : "";
//            $shopName = isset($_POST['shop_name']) ? $_POST['shop_name'] : "";
//            $shopLandLine = isset($_POST['shop_landline']) ? $_POST['shop_landline'] : "";
//            $shopAddress = isset($_POST['shop_addr']) ? $_POST['shop_addr'] : "";
//            $shopType = isset($_POST['shop_type']) ? $_POST['shop_type'] : "";
//            $shopMemNum = isset($_POST['shop_mem_num']) ? $_POST['shop_mem_num'] : "";
//            $shopOperator = isset($_POST['shop_operator']) ? $_POST['shop_operator'] : "";
//            $shopContact2 = isset($_POST['shop_contact2']) ? $_POST['shop_contact2'] : "";
//            $shopContact1 = isset($_POST['shop_contact1']) ? $_POST['shop_contact1'] : "";
//            $shopGroupNet = isset($_POST['shop_group_net']) ? $_POST['shop_group_net'] : "";
//            $shopStreet = isset($_POST['shop_street']) ? $_POST['shop_street'] : "";
//            $shopBroadbandCover = isset($_POST['shop_broadband_cover']) ? $_POST['shop_broadband_cover'] : "";
//
//            $sh = new Shop();
//            $sh->setTime(date("Y-m-d H:i:s"));
//            $sh->setShopUser($userObj);
//            $sh->setShop209($shop209);
//            $sh->setShopName($shopName);
//            $sh->setShopLandline($shopLandLine);
//            $sh->setShop280($shop280);
//            $sh->setShopAddr($shopAddress);
//            $sh->setShopBroadbandCover($shopBroadbandCover);
//            $sh->setShopContact1($shopContact1);
//            $sh->setShopContact2($shopContact2);
//            $sh->setShopMemNum($shopMemNum);
//            $sh->setShopGroupNet($shopGroupNet);
//            $sh->setShopOperator($shopOperator);
//            $sh->setShopStreet($shopStreet);
//            $sh->setShopType($shopType);
//            $sh->setShopLng($shopLng);
//            $sh->setShopLat($shopLat);
//            $code = '';
//            for ($i = 0; $i < 7; $i++) {
//                $code .= range('a', 'z')[random_int(0, 25)];
//            }
//            $sh->setShopUniqueCode($code);
//            $this->entityManager->persist($sh);
//            $this->entityManager->flush($sh);
//            $ret = true;
//        } catch (Exception $e) {
//            $ret = false;
//            echo json_encode(array("message" => $e->getMessage(), "error" => "error"));
//        }
//        if ($ret) {
//            echo json_encode(array("message" => "添加成功!"));
//        }
//    }

    private
    function searchKeyWord($keyWord)
    {
        $a = "SELECT * FROM shop WHERE  shop_name LIKE  '%" . $keyWord . "%'";
        $stmt = $this->entityManager->getConnection()->prepare($a);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    private
    function actionEditShop($id)
    {
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

            $shopEntity = $this->shopRepo->find($id);
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
            $this->entityManager->flush($shopEntity);

        } catch (Exception $e) {
            echo json_encode(array("message" => "保存失败," . $e->getMessage(), "error" => "error"));
        }
        echo json_encode(array("message" => "修改成功！"));
    }

}

new MainApp();
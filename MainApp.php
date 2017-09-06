<?php
ini_set('display_errors', 1);
ini_set('date.timezone','Asia/Shanghai');

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
            $shopArr = $this->shopRepo->findAll();
            if (is_null($shopArr) || count($shopArr) < 1) {
                echo json_encode(array("message" => "没有符合搜索的结果", "errorCode" => 100));
                return;
            }
            $retArr = array();
            if ($this->loginUserName == "88888888") {

                $adminUser = $this->userRepo->findOneBy(array("account_name" => "88888888"));
                if ($adminUser instanceof User) {
                    $shops = $adminUser->getAssignedShop();
                    foreach ($shops as $shop) {
                        if ($shop instanceof Shop) {
                            $st = similar_text($shop->getShopName(), $keyWord);
                            if ($st > 0) {
                                $item = $shop->toArray();
                                $item["owner"] = 1;
                                $retArr[] = $item;
                            }
                        }
                    }
                }
                foreach ($shopArr as $shop) {
                    if ($shop instanceof Shop) {
                        $u = $shop->getShopUser();
                        if ($u instanceof User && $u->getAccountName() == "88888888") continue;
                        $st = similar_text($shop->getShopName(), $keyWord);
                        if ($st > 0) {
                            $item = $shop->toArray();
                            $item["owner"] = 0;
                            $retArr[] = $item;
                        }
                    }
                }
            } else {

                if ($this->loginUserName == "yanjiang") {
                    $users = $this->userRepo->findBy(array("county" => "雁江"));
                    foreach ($users as $u) {
                        if ($u instanceof User) {
                            $a = $u->getAssignedShop();
                            foreach ($a as $sh) {
                                $retArr [] = $sh->toArray();
                            }
                        }
                    }
                } else if ($this->loginUserName == "anyue") {
                    $users = $this->userRepo->findBy(array("county" => "安岳"));
                    foreach ($users as $u) {
                        if ($u instanceof User) {
                            $a = $u->getAssignedShop();
                            foreach ($a as $sh) {
                                $retArr [] = $sh->toArray();
                            }
                        }
                    }
                } else if ($this->loginUserName == "lezhi") {
                    $users = $this->userRepo->findBy(array("county" => "乐至"));
                    foreach ($users as $u) {
                        if ($u instanceof User) {
                            $a = $u->getAssignedShop();
                            foreach ($a as $sh) {
                                $retArr [] = $sh->toArray();
                            }
                        }
                    }
                } else {
                    foreach ($shopArr as $shop) {
                        if ($shop instanceof Shop) {
                            $st = similar_text($shop->getShopName(), $keyWord);
                            if ($st > 0) {
                                $userModel = $shop->getShopUser();
                                if ($userModel instanceof User && $userModel->getAccountName() == $this->loginUserName) {
                                    $retArr[] = $shop->toArray();
                                }
                            }

                        }
                    }
                }
            }
            echo json_encode($retArr);
        } else if ($actionName == MainApp::$SAVE_SHOP) {
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
        $ret = false;
        try {
            $shopRep = $this->entityManager->getRepository("Shop");
            if ($shopRep instanceof ShopRepository) {
                $shopRep->addShop($shopRep->getShopArrayFromRequest($_REQUEST), $userObj);
            }
            $ret = true;
        } catch (Exception $e) {
            $ret = false;
            echo json_encode(array("message" => $e->getMessage(), "error" => "error"));
        }
        if ($ret) {
            echo json_encode(array("message" => "添加成功!"));
        }
    }

    private function actionEditShop($id)
    {
        try {
            $shopRep = $this->entityManager->getRepository("Shop");
            if ($shopRep instanceof ShopRepository) {
                $shopObj = $shopRep->getShopArrayFromRequest($_REQUEST);
                $shopEntity = $shopRep->find($id);
                $shopEntity->setShop209($shopObj['shop209']);
                $shopEntity->setShopName($shopObj['shopName']);
                $shopEntity->setShopLandline($shopObj['shopLandLine']);
                $shopEntity->setShop280($shopObj['shop280']);
                $shopEntity->setShopAddr($shopObj['shopAddress']);
                $shopEntity->setShopBroadbandCover($shopObj['shopBroadbandCover']);
                $shopEntity->setShopContact1($shopObj['shopContact1']);
                $shopEntity->setShopContact2($shopObj['shopContact2']);
                $shopEntity->setShopMemNum($shopObj['shopMemNum']);
                $shopEntity->setShopGroupNet($shopObj['shopGroupNet']);
                $shopEntity->setShopOperator($shopObj['shopOperator']);
                $shopEntity->setShopStreet($shopObj['shopStreet']);
                $shopEntity->setShopType($shopObj['shopType']);
                $shopEntity->setShopLng($shopObj['shopLng']);
                $shopEntity->setShopLat($shopObj['shopLat']);
                $this->entityManager->persist($shopEntity);
                $this->entityManager->flush();
            }
        } catch (Exception $e) {
            echo json_encode(array("message" => "保存失败," . $e->getMessage(), "error" => "error"));
        }
        echo json_encode(array("message" => "修改成功！"));
    }

}

new MainApp();
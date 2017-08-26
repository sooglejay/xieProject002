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

class Search extends App
{
    public static $INDEX = "INDEX";
    public static $SEARCH = "search";
    public static $SAVE_SHOP = "save_shop";

    private $userRepo;
    private $shopRepo;

    /**
     * Search constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->userRepo = $this->entityManager->getRepository('User');
        $this->shopRepo = $this->entityManager->getRepository('Shop');
        $actionName = $_REQUEST['action'];


        if ($actionName == Search::$INDEX) {
            $userModels = $this->userRepo->findAll();
            if (count($userModels) > 0) {
                $model = $userModels[0];
                assert($model instanceof User);
                $arr = $model->toArray();
                echo json_encode($arr);
            }

        } else if ($actionName == Search::$SEARCH) {
            $retArr = [];
            $keyWord = $_REQUEST['keyWord'];
        } else if ($actionName == Search::$SAVE_SHOP) {
            $this->actionSaveShop();
        }
    }

    private function actionSaveShop()
    {
        $shopName = $_REQUEST['shop_name'];
        $shops = $this->shopRepo->findAll();
        foreach ($shops as $addedShop) {
            assert($addedShop instanceof Shop);
            if ($addedShop->getShopName() == $shopName) {
                echo json_encode(array("message"=>"店铺名已经存在，请重新输入！"));
                return;
            }
        }
        $shop = new Shop();
        $shop->setShopName(isset($_REQUEST['shop_name']) ? $_REQUEST['shop_name'] : "");
        $shop->setShopAddr(isset($_REQUEST['shop_addr']) ? $_REQUEST['shop_addr'] : "");
        $shop->setShopStreet(isset($_REQUEST['shop_street']) ? $_REQUEST['shop_street'] : "");
        $shop->setShopContact1(isset($_REQUEST['shop_contact1']) ? $_REQUEST['shop_contact1'] : "");
        $shop->setShopContact2(isset($_REQUEST['shop_contact2']) ? $_REQUEST['shop_contact2'] : "");
        $shop->setShopType(isset($_REQUEST['shop_type']) ? $_REQUEST['shop_type'] : "");
        $shop->setShop280(isset($_REQUEST['shop_280']) ? $_REQUEST['shop_280'] : "");
        $shop->setShopGroupNet(isset($_REQUEST['shop_group_net']) ? $_REQUEST['shop_group_net'] : "");
        $shop->setShopMemNum(isset($_REQUEST['shop_mem_num']) ? $_REQUEST['shop_mem_num'] : "");
        $shop->setShop209(isset($_REQUEST['shop_209']) ? $_REQUEST['shop_209'] : "");
        $shop->setShopBroadbandCover(isset($_REQUEST['shop_broadband_cover']) ? $_REQUEST['shop_broadband_cover'] : "");
        $shop->setShopLandline(isset($_REQUEST['shop_landline']) ? $_REQUEST['shop_landline'] : "");
        $shop->setShopOperator(isset($_REQUEST['shop_operator']) ? $_REQUEST['shop_operator'] : "");
        $shop->setLng(isset($_REQUEST['lng']) ? $_REQUEST['lng'] : "");
        $shop->setLat(isset($_REQUEST['lat']) ? $_REQUEST['lat'] : "");
        $this->entityManager->persist($shop);
        $this->entityManager->flush();
        echo json_encode(array("errcode"=>0,"message"=>"添加成功！"));
    }
}
$s = new Search();

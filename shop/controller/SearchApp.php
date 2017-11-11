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

class SearchApp extends App
{
    public function __construct()
    {
        parent::__construct();
        if ($this->isLogin()) {
            echo json_encode(array("code" => 200, "message" => "successfully!", "data" => $this->doShowPage()));
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
        $userEntity = $userRepo->findOneBy(array("openId" => $openId));
        if (is_null($userEntity) || !($userEntity instanceof User)) {
            return array("code" => 201, "message" => "未能获取用户信息，请重新登录", "error" => "error");
        }
        $keyWord = $_POST['search'];
        $accountName = $userEntity->getAccountName();
        if ($accountName == "88888888") {
            //总账号
            $searchResultArr = $this->searchShopForAdmin($keyWord, $userEntity->getId());
        } else {
            $ar = array("yanjiang" => "雁江", "anyue" => "安岳", "lezhi" => "乐至");
            if (isset($ar[$accountName])) {
                // 区县总账号
                $searchResultArr = $this->searchShopForAreaAdmin($keyWord, $userEntity->getCounty());
            } else {
                //平民老百姓
                $searchResultArr = $this->searchShopForSimplePeople($keyWord, $userEntity->getId());
            }
        }
        return $searchResultArr;
    }

    private function searchShopForAdmin($keyWord, $adminUserId)
    {
        $a = "SELECT * , IF(sh.shopUser_id=$adminUserId,1,0) as owner FROM shop as sh WHERE  shop_name LIKE  '%" . $keyWord . "%'";
        $stmt = $this->entityManager->getConnection()->prepare($a);
        $stmt->execute();
        return $stmt->fetchAll();
    }


    private function searchShopForAreaAdmin($keyWord, $countyName)
    {
        $a = "SELECT *,true as owner from `shop` as s,`user` as u where  u.county ='" . $countyName . "'  and  s.shop_name LIKE  '%" . $keyWord . "%'";
        $stmt = $this->entityManager->getConnection()->prepare($a);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    private function searchShopForSimplePeople($keyWord, $userId)
    {
        $a = "SELECT s.* , true as owner from `shop` as s,`user` as u where  u.id ='" . $userId . "'  and  s.shop_name LIKE  '%" . $keyWord . "%'";
        $stmt = $this->entityManager->getConnection()->prepare($a);
        $stmt->execute();
        return $stmt->fetchAll();
    }


}

new SearchApp();
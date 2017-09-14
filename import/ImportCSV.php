<?php
/**
 * Created by PhpStorm.
 * User: sooglejay
 * Date: 17/9/14
 * Time: 10:47
 */

// 这个是导入当前目录下 9_12.csv文件这里面的shop数据 到shop表
// 我发现使用CSV之后快了不止一点点，比Excel快了将近20倍，内存使用减少20倍


date_default_timezone_set("PRC");

require_once './../lib/PHPExcel_1_7_9/Classes/PHPExcel/IOFactory.php';
require_once './../lib/PHPExcel_1_7_9/Classes/PHPExcel.php';

require_once "./../bootstrap.php";
require_once "./../model/User.php";
require_once "./../model/BuyTypeUser.php";
require_once "./../model/ActivitySepUser.php";
ini_set('memory_limit', '-1');
ini_set('max_execution_time', 300000); //300 seconds = 5 minutes
class Imp extends App
{

    private $userRepo;

    /**
     * ImportCSV constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->userRepo = $this->entityManager->getRepository('User');
        $this->admin_import();
    }

    private function admin_import()
    {

        $file = "./9_12.csv";
        $handle = fopen($file, "r");
        $firstItem = true;
        $k = 0;
        $len = 0;
        if ($file != NULL) {
            $activitySepRepo = $this->entityManager->getRepository("Shop");
            if ($activitySepRepo instanceof ShopRepository) {
                $existsArr = $activitySepRepo->findAll();
                foreach ($existsArr as $entity) {
                    if ($entity instanceof Shop) {
                        $userE = $entity->getShopUser();
                        if ($userE instanceof User) {
                            $userE->clearShops();
                        }
                        $this->entityManager->remove($entity);
                    }
                }
                $this->entityManager->flush();
            }
            while (($row = fgetcsv($handle, 1000, ",")) !== false) {
                if ($firstItem) {
                    $firstItem = false;
                    continue;
                }
                $userName = $row[6];
                $userEntity = $this->userRepo->findOneBy(array("account_name" => $userName));
                $k++;
                $shop = new Shop();
                $shop->setShopUniqueCode(isset($row[7]) ? $row[7] : "");
                $shop->setShopName(isset($row[8]) ? $row[8] : "");
                $shop->setShopAddr(isset($row[9]) ? $row[9] : "");
                $shop->setShopContact1(isset($row[10]) ? $row[10] : "");
                $shop->setShopContact2(isset($row[11]) ? $row[11] : "");
                $shop->setShopType(isset($row[12]) ? $row[12] : "");
                $shop->setShopStreet(isset($row[13]) ? $row[13] : "");
                $shop->setShopLng(isset($row[14]) ? $row[14] : "");
                $shop->setShopLat(isset($row[15]) ? $row[15] : "");
                $shop->setShop280(isset($row[16]) ? $row[16] : "");
                $shop->setShop209(isset($row[17]) ? $row[17] : "");
                $shop->setShopGroupNet(isset($row[18]) ? $row[18] : "");
                $shop->setShopMemNum(isset($row[19]) ? $row[19] : "");
                $shop->setShopBroadbandCover(isset($row[20]) ? $row[20] : "");
                $shop->setShopLandline(isset($row[21]) ? $row[21] : "");
                $shop->setShopOperator(isset($row[22]) ? $row[22] : "");
                $shop->setTime(strtotime(isset($row[23]) ? $row[23] : date("m/d/Y H:i:s") . ""));
                $shop->setShopUser($userEntity);
                $this->entityManager->persist($shop);
                if ($k % 1000 == 0) {
                    $this->entityManager->flush();
                    $k = 0;
                }
                $len++;
            }
            if ($k > 0) {
                $this->entityManager->flush();
            }
            echo "\n size = " . $len . "\n";
        }
    }
}

new Imp();
?>
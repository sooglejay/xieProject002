<?php
/**
 * Created by PhpStorm.
 * User: sooglejay
 * Date: 17/8/23
 * Time: 23:05
 */
date_default_timezone_set("PRC");
require_once dirname(__FILE__) . '/../lib/PHPExcel_1_7_9/Classes/PHPExcel/IOFactory.php';
require_once dirname(__FILE__) . '/../lib/PHPExcel_1_7_9/Classes/PHPExcel.php';
require_once dirname(__FILE__) . "/../bootstrap.php";
require_once dirname(__FILE__) . "/../model/User.php";
require_once dirname(__FILE__) . "/../model/Shop.php";
ini_set('memory_limit', '-1');
ini_set('max_execution_time', 300000); //300 seconds = 5 minutes

class ExcelHandler extends App
{
    private $userRepo;

    public function __construct()
    {
        parent::__construct();
        $this->userRepo = $this->entityManager->getRepository('User');
        $this->setupCache();

    }

    private function setupCache()
    {
        try {
            $cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_discISAM;
            $cacheSettings = array(
                'dir' => dirname(__FILE__).'/tmp'
            );
            PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

            $cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
            $cacheSettings = array(
                'memoryCacheSize' => '700MB'
            );
            if (!PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings)) {
                $responseToAjaxCall['error'] = $cacheMethod . " caching method is not available";
                die(json_encode($responseToAjaxCall));
            }
        } catch (Exception $ex) {
            throw new \Exception("Excel Setup Cache Exception: " . $ex->getMessage());
        }
    }

    public function doDownload()
    {
        $objPHPExcel = new PHPExcel();

        /*以下是一些设置 ，什么作者  标题啊之类的*/
        $objPHPExcel->getProperties()->setCreator("蒋维")
            ->setLastModifiedBy("蒋维")
            ->setTitle("商铺录入信息")
            ->setSubject("商铺录入信息")
            ->setDescription("备份数据")
            ->setKeywords("商铺录入信息")
            ->setCategory("result file");

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', "地市")
            ->setCellValue('B1', "区县")
            ->setCellValue('C1', "认领渠道编码")
            ->setCellValue('D1', "营业厅名称")
            ->setCellValue('E1', "名称")
            ->setCellValue('F1', "网格名称")
            ->setCellValue('G1', "帐号")
            ->setCellValue('H1', "唯一编号")
            ->setCellValue('I1', "商铺名称")
            ->setCellValue('J1', "商铺地址")
            ->setCellValue('K1', "联系方式1")
            ->setCellValue('L1', "联系方式2")
            ->setCellValue('M1', "大类")
            ->setCellValue('N1', "街道")
            ->setCellValue('O1', "经度")
            ->setCellValue('P1', "纬度")
            ->setCellValue('Q1', "280代码")
            ->setCellValue('R1', "209代码")
            ->setCellValue('S1', "是否完成集团组网")
            ->setCellValue('T1', "集团成员数")
            ->setCellValue('U1', "宽带是否覆盖")
            ->setCellValue('V1', "商务动力座机")
            ->setCellValue('W1', "使用运营商")
            ->setCellValue('X1', "添加时间");

        $shopRep = $this->entityManager->getRepository("Shop");
        $shops = $shopRep->findAll();
        $row = 2;
        foreach ($shops as $shop) {
            if ($shop instanceof Shop) {
                $userModel = $shop->getShopUser();
                if ($userModel instanceof User) {
                    $timeStr = $shop->getTime();
                    if (strlen($timeStr) <= 11) {
                        $timeStr = date("Y-m-d H:i:s", $timeStr);
                    }
                    $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A' . $row, $userModel->getCity())
                        ->setCellValue('B' . $row, $userModel->getCounty())
                        ->setCellValue('C' . $row, $userModel->getCode())
                        ->setCellValue('D' . $row, $userModel->getSellingAreaName())
                        ->setCellValue('E' . $row, $userModel->getAreaName())
                        ->setCellValue('F' . $row, $userModel->getGridName())
                        ->setCellValue('G' . $row, $userModel->getAccountName())
                        ->setCellValue('H' . $row, $shop->getShopUniqueCode())
                        ->setCellValue('I' . $row, $shop->getShopName())
                        ->setCellValue('J' . $row, $shop->getShopAddr())
                        ->setCellValue('K' . $row, $shop->getShopContact1())
                        ->setCellValue('L' . $row, $shop->getShopContact2())
                        ->setCellValue('M' . $row, $shop->getShopType())
                        ->setCellValue('N' . $row, $shop->getShopStreet())
                        ->setCellValue('O' . $row, $shop->getShopLng())
                        ->setCellValue('P' . $row, $shop->getShopLat())
                        ->setCellValue('Q' . $row, $shop->getShop280())
                        ->setCellValue('R' . $row, $shop->getShop209())
                        ->setCellValue('S' . $row, $shop->getShopGroupNet())
                        ->setCellValue('T' . $row, $shop->getShopMemNum())
                        ->setCellValue('U' . $row, $shop->getShopBroadbandCover())
                        ->setCellValue('V' . $row, $shop->getShopLandline())
                        ->setCellValue('W' . $row, $shop->getShopOperator())
                        ->setCellValue('X' . $row, $timeStr);
                    $row++;
                }
            }
        }
        $objPHPExcel->getActiveSheet()->setTitle(date("Y-m-d") . '商铺录入信息');
        $objPHPExcel->setActiveSheetIndex(0);
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save(str_replace('.php', '.xls', __FILE__));
        $fullPath = __DIR__ . "/tmp/";
        @array_map('unlink', glob("$fullPath*.cache"));
    }
}




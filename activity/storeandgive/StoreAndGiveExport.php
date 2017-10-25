<?php
/**
 * Created by PhpStorm.
 * User: sooglejay
 * Date: 17/8/23
 * Time: 23:05
 */
date_default_timezone_set("PRC");

require_once dirname(__FILE__) . './../../lib/PHPExcel_1_7_9/Classes/PHPExcel/IOFactory.php';
require_once dirname(__FILE__) . './../../lib/PHPExcel_1_7_9/Classes/PHPExcel.php';

require_once dirname(__FILE__) . "./../../bootstrap.php";
require_once dirname(__FILE__) . "./../../model/User.php";
require_once dirname(__FILE__) . "./../../model/BuyTypeUser.php";
require_once dirname(__FILE__) . "./../../model/StoreAndGive.php";
require_once dirname(__FILE__) . "./../../model/ActivitySepUser.php";
ini_set('memory_limit', '800M');
ini_set('max_execution_time', 30000); //300 seconds = 5 minutes

class StoreAndGiveExport extends App
{

    public function __construct()
    {
        parent::__construct();
        $this->setupCache();
    }

    private function setupCache()
    {
        try {
            $cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_discISAM;
            $cacheSettings = array(
                'dir' => './tmp'
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
            ->setTitle("移动用户存费送费活动预约信息")
            ->setSubject("移动用户存费送费活动预约信息")
            ->setDescription("移动活动")
            ->setKeywords("移动用户存费送费活动预约信息")
            ->setCategory("result file");

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', "姓名")
            ->setCellValue('B1', "手机号码")
            ->setCellValue('C1', "身份证号")
            ->setCellValue('D1', "区县")
            ->setCellValue('E1', "地址")
            ->setCellValue('F1', "预约时间");

        $sagRep = $this->entityManager->getRepository("StoreAndGive");
        $allSagEntity = $sagRep->findAll();
        $row = 2;
        foreach ($allSagEntity as $entity) {
            if ($entity instanceof StoreAndGive) {
                $timeStr = $entity->getTime();
                if (strlen($timeStr) < 2) {
                    $timeStr = date("Y-m-d H:i:s");
                } else if (strlen($timeStr) <= 11) {
                    $timeStr = date("Y-m-d H:i:s", $timeStr);
                }
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $row, $entity->getUserName())
                    ->setCellValue('B' . $row, $entity->getPhoneNumber())
                    ->setCellValue('C' . $row, $entity->getIdCard())
                    ->setCellValue('D' . $row, $entity->getArea())
                    ->setCellValue('E' . $row, $entity->getAddress())
                    ->setCellValue('F' . $row, $timeStr);
                $row++;
            }
        }
        $objPHPExcel->getActiveSheet()->setTitle(date("Y-m-d")."_sheet");
        $objPHPExcel->setActiveSheetIndex(0);
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $filename = str_replace('.php', '.xls', __FILE__);
        $objWriter->save($filename);
//        $filename = dirname("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]") . "/StoreAndGiveExport.xls";
//        header("Location: $filename");

    }
}

$t = new StoreAndGiveExport();
try{
    @$t->doDownload();
    echo json_encode(array("message" => "good", "code" => 200));
}catch (Exception $e){
    echo json_encode(array("message" => "good", "error" => 200));
}





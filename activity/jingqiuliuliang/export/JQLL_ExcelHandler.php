<?php
/**
 * Created by PhpStorm.
 * User: sooglejay
 * Date: 17/8/23
 * Time: 23:05
 */
date_default_timezone_set("PRC");

require_once dirname(__FILE__) . '/../../../lib/PHPExcel_1_7_9/Classes/PHPExcel/IOFactory.php';
require_once dirname(__FILE__) . '/../../../lib/PHPExcel_1_7_9/Classes/PHPExcel.php';

require_once dirname(__FILE__) . "/../../../bootstrap.php";
require_once dirname(__FILE__) . "/../../../model/jingqiuliuliang/JQLL_User.php";
ini_set('memory_limit', '-1');
ini_set('max_execution_time', 30000); //300 seconds = 5 minutes

class JQLL_ExcelHandler extends App
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
            ->setTitle("金秋流量敞开用")
            ->setSubject("移动用户活动预订")
            ->setDescription("备份数据")
            ->setKeywords("录入信息")
            ->setCategory("result file");

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', "手机号码")
            ->setCellValue('B1', "资费编号")
            ->setCellValue('C1', "资费名称")
            ->setCellValue('D1', "预订套餐档次")
            ->setCellValue('E1', "预订时间");

        $userRepo = $this->entityManager->getRepository("JQLL_User");
        $users = $userRepo->findBy(array("isChosen"=>1));
        $row = 2;
        foreach ($users as $userEntity) {
            if ($userEntity instanceof JQLL_User) {
                $timeStr = $userEntity->getTime();
                if (strlen($timeStr) <= 11) {
                    $timeStr = date("Y-m-d H:i:s", $timeStr);
                }
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $row, $userEntity->getMobileNumber())
                    ->setCellValue('B' . $row, $userEntity->getZifeiCode())
                    ->setCellValue('C' . $row, $userEntity->getZifeiName())
                    ->setCellValue('D' . $row, $userEntity->getType() . "元档活动")
                    ->setCellValue('E' . $row, $timeStr);
                $row++;
            }
        }

        $objPHPExcel->getActiveSheet()->setTitle(date("Y-m-d") . '金秋流量预订信息');
        $objPHPExcel->setActiveSheetIndex(0);
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save(str_replace('.php', '.xls', __FILE__));
        $fullPath = __DIR__ . "/tmp/";
        @array_map('unlink', glob("$fullPath*.cache"));
    }
}




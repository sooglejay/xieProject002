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
require_once dirname(__FILE__) . "./../../model/ActivitySepUser.php";
ini_set('memory_limit', '800M');
ini_set('max_execution_time', 30000); //300 seconds = 5 minutes

class ExportExcel extends App
{
    private $xlsFile;
    private $xlsSheetName;
    private $objReader;
    private $objPHPExcel;
    private $userRepo;

    public static $ACTION_DOWNLOAD = "download";
    public static $ACTION_DOWNLOAD_SEP = "download_sep";

    public function __construct()
    {
        parent::__construct();
        $this->userRepo = $this->entityManager->getRepository('User');
        $this->setupCache();
        $this->doDownload();

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

    private function getXlsType()
    {
        $xlsType = "Excel2007";
        $arr = explode('.', $this->xlsFile);
        $type = end($arr);
        if ($type == "xls") {
            $xlsType = "Excel5";
        }
        return $xlsType;
    }

    public function doExport()
    {

        $dataArray = $this->getSheetData();

        if (count($dataArray) > 0) {//若有需要导入，就先清空数据库
            $existsArr = $this->userRepo->findAll();
            foreach ($existsArr as $entity) {
                assert($entity instanceof User);
                $this->entityManager->remove($entity);
            }
            $this->entityManager->flush();
        }
        $i = 0;
        $firstItem = true;
        foreach ($dataArray as $index => $row) {
            if ($firstItem) {
                $firstItem = false;
                continue;
            }

            $i++;
            $user = new User();
            $user->setCity($row['A']);
            $user->setCounty($row['B']);
            $user->setCode($row['C']);
            $user->setSellingAreaName($row['D']);
            $user->setAreaName($row['E']);
            $user->setGridName($row['F']);
            $user->setAccountName($row['G']);
            $user->setShopNum(0);
            $this->entityManager->persist($user);
            if ($i % 20 == 0) {
                $this->entityManager->flush();
                $i = 0;
            }
        }
        if ($i > 0) {
            $this->entityManager->flush();
        }
        $userArr = $this->userRepo->findAll();
        echo "\n size = " . count($userArr) . "\n";
    }

    public function doExportActivity()
    {
        $dataArray = $this->getSheetData();
        $activitySepRepo = $this->entityManager->getRepository("ActivitySepUser");

        if (count($dataArray) > 0) {//若有需要导入，就先清空数据库
            $existsArr = $activitySepRepo->findAll();
            foreach ($existsArr as $entity) {
                assert($entity instanceof ActivitySepUser);
                $this->entityManager->remove($entity);
            }
            $this->entityManager->flush();
        }
        $i = 0;
        $firstItem = true;
        $len = 0;
        foreach ($dataArray as $index => $row) {
            if ($firstItem) {
                $firstItem = false;
                continue;
            }

            $i++;
            $user = new ActivitySepUser();
            $user->setMobileNumber($row['A']);
            $user->setType88($row['B'] == "是");
            $user->setType138($row['C'] == "是");
            $user->setType158($row['D'] == "是");
            $user->setType238($row['E'] == "是");
            $this->entityManager->persist($user);
            if ($i % 20 == 0) {
                $this->entityManager->flush();
                $i = 0;
            }
            $len++;
        }
        if ($i > 0) {
            $this->entityManager->flush();
        }
        echo "\n size = " . $len . "\n";
    }

    function array_to_csv_download($array, $filename = "export.csv", $delimiter = ";")
    {
        // open raw memory as file so no temp files needed, you might run out of memory though
        $f = fopen('php://memory', 'w');
        // loop over the input array
        foreach ($array as $line) {
            // generate csv lines from the inner arrays
            fputcsv($f, $line, $delimiter);
        }
        // reset the file pointer to the start of the file
        fseek($f, 0);
        // tell the browser it's going to be a csv file
        header('Content-Type: application/csv');
        // tell the browser we want to save it instead of displaying it
        header('Content-Disposition: attachment; filename="' . $filename . '";');
        // make php send the generated csv lines to the browser
        fpassthru($f);
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
                if (strlen($timeStr) <= 11) {
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
        $objPHPExcel->getActiveSheet()->setTitle(date("Y-m-d") . '商铺录入信息');
        $objPHPExcel->setActiveSheetIndex(0);
//        header('Content-Type: application/vnd.ms-excel');
//        header('Content-Disposition: attachment;filename="商铺信息.xls"');
//        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');


        $filename = str_replace('.php', '.xls', __FILE__);
        @unlink($filename);
        $objWriter->save($filename);
        $fileinfo = pathinfo($filename);
        header('Content-type: application/x-'.$fileinfo['extension']);
        header('Content-Disposition: attachment; filename='.$fileinfo['basename']);
        header('Content-Length: '.filesize($filename));
        readfile($filename);
        exit();
    }
}

$d = new ExportExcel();





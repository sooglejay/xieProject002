<?php
namespace BaZhong;

/**
 * Created by PhpStorm.
 * User: sooglejay
 * Date: 17/11/5
 * Time: 12:36
 */


use App;
use Exception;
use PHPExcel_IOFactory;

require_once dirname(__FILE__) . './../../../../lib/PHPExcel_1_7_9/Classes/PHPExcel/IOFactory.php';
require_once dirname(__FILE__) . './../../../../lib/PHPExcel_1_7_9/Classes/PHPExcel.php';

require_once dirname(__FILE__) . "./../../../../bootstrap.php";
require_once dirname(__FILE__) . "./../model/User.php";
require_once dirname(__FILE__) . "./../model/AllPhoneSegments.php";


ini_set('memory_limit', '-1');
ini_set('max_execution_time', 30000); //300 seconds = 5 minutes


class ImportDataFromExcel extends App
{
    private $xlsFile;
    private $xlsSheetName;
    private $objReader;
    private $objPHPExcel;

    public function __construct()
    {
        parent::__construct();
//        $this->saveAllPhoneSegments(dirname(__FILE__) . "/../excels/all.xls", 'Sheet1');
        $this->savePhoneSegments(dirname(__FILE__) . "/../excels/bazhong.xls", "Sheet1");
    }

    public function getSheetData()
    {
        try {
            $this->objReader->setLoadSheetsOnly($this->xlsSheetName);
            //$filterSubset = new MyReadFilter($_POST['start'],$_POST['end'],range('A','S'));
            //$objReader->setReadFilter($filterSubset);
            $this->objPHPExcel = $this->objReader->load($this->xlsFile);
            $sheetData = $this->objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
        } catch (Exception $ex) {
            throw new \Exception("Excel Load Sheet Exception: " . $ex->getMessage());
        }
        return $sheetData;
    }

    private function setupCache()
    {
        try {
            $cacheMethod = \PHPExcel_CachedObjectStorageFactory::cache_in_memory_gzip;
            if (!\PHPExcel_Settings::setCacheStorageMethod($cacheMethod)) {
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

    public function configExcelAfterInit($path, $xlsSheetName)
    {
        $this->xlsFile = $path;
        $this->xlsSheetName = $xlsSheetName;
        $this->setupCache();
        try {
            $type = $this->getXlsType();
            $this->objReader = PHPExcel_IOFactory::createReader($type);
            $this->objReader->setReadDataOnly(true);
        } catch (Exception $ex) {
            throw new \Exception("Excel Create Reader Exception: " . $ex->getMessage());
        }
    }

    public function savePhoneSegments($path, $xlsSheetName)
    {
        $this->configExcelAfterInit($path, $xlsSheetName);
        $dataArray = $this->getSheetData();
        echo count($dataArray) . "\n";
        $len = 0;
        $allPhoneSegmentsRepo = $this->entityManager->getRepository('BaZhong\AllPhoneSegments');
        if ($allPhoneSegmentsRepo instanceof BZAllPhoneSegmentsRepository) {
            foreach ($dataArray as $index => $row) {
                $phoneNumber = isset($row['A']) ? $row['A'] : "";
                $allPhoneSegmentsRepo->savePhoneSegment($phoneNumber);
                $len++;
                if ($len % 10000 == 0) {
                    $this->entityManager->flush();
                    echo "\n size = " . $len . "\n";
                }
            }
            $this->entityManager->flush();
            echo "\n total size = " . $len . "\n";
        }
    }
}

new ImportDataFromExcel();







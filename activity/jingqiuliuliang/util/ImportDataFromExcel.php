<?php
/**
 * Created by PhpStorm.
 * User: sooglejay
 * Date: 17/11/5
 * Time: 12:36
 */


require_once dirname(__FILE__) . './../../../lib/PHPExcel_1_7_9/Classes/PHPExcel/IOFactory.php';
require_once dirname(__FILE__) . './../../../lib/PHPExcel_1_7_9/Classes/PHPExcel.php';

require_once dirname(__FILE__) . "./../../../bootstrap.php";
require_once dirname(__FILE__) . "./../../../model/jingqiuliuliang/JQLL_User.php";
ini_set('memory_limit', '1024M');
ini_set('max_execution_time', 30000); //300 seconds = 5 minutes


class ImportDataFromExcel extends App
{
    private $xlsFile;
    private $xlsSheetName;
    private $objReader;
    private $objPHPExcel;
    private $userRepo;


    public function __construct()
    {
        parent::__construct();
        $this->userRepo = $this->entityManager->getRepository('JQLL_User');
        if ($this->userRepo instanceof JQLL_UserRepository) {
            $this->userRepo->deleteAll();
        }
        $this->doParseExcel(dirname(__FILE__) . "/../excels/28.xlsx", 'Sheet1', 28);
        $this->doParseExcel(dirname(__FILE__) . "/../excels/38.xlsx", 'Sheet1', 38);
        $this->doParseExcel(dirname(__FILE__) . "/../excels/48.xlsx", 'Sheet1', 48);
        $this->doParseExcel(dirname(__FILE__) . "/../excels/58.xlsx", 'Sheet1', 58);
        $this->doParseExcel(dirname(__FILE__) . "/../excels/88.xlsx", 'Sheet1', 88);
        $this->doParseExcel(dirname(__FILE__) . "/../excels/138.xlsx", 'Sheet1', 138);
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

    public function doParseExcel($path, $xlsSheetName, $type)
    {
        $this->configExcelAfterInit($path, $xlsSheetName);
        $dataArray = $this->getSheetData();
        $i = 0;
        $firstItem = true;
        $len = 0;
        foreach ($dataArray as $index => $row) {
            if ($firstItem) {
                $firstItem = false;
                continue;
            }
            $entity = new JQLL_User();
            $entity->setMobileNumber(isset($row['A']) ? $row['A'] : "");
            $entity->setZifeiCode(isset($row['B']) ? $row['B'] : "");
            $entity->setZifeiName(isset($row['C']) ? $row['C'] : "");
            $entity->setType($type);
            $entity->setAddress('');
            $entity->setIsChosen(0);
            $i++;
            $this->entityManager->persist($entity);
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
}


new ImportDataFromExcel();







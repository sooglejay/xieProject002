<?php
namespace End_2017;

/**
 * Created by PhpStorm.
 * User: sooglejay
 * Date: 17/11/5
 * Time: 12:36
 */


use App;
use Exception;
use PHPExcel_IOFactory;

require_once dirname(__FILE__) . './../../../lib/PHPExcel_1_7_9/Classes/PHPExcel/IOFactory.php';
require_once dirname(__FILE__) . './../../../lib/PHPExcel_1_7_9/Classes/PHPExcel.php';

require_once dirname(__FILE__) . "./../../../bootstrap.php";
require_once dirname(__FILE__) . "./../model/User.php";
require_once dirname(__FILE__) . "./../model/UserType.php";
require_once dirname(__FILE__) . "./../model/ActivityType.php";
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
        $this->saveUserDiffByType(dirname(__FILE__) . "/../excels/type_1.xlsx", '百日冲刺0-100M目标客户', 1);
        $this->saveUserDiffByType(dirname(__FILE__) . "/../excels/type_2.xlsx", '百日冲刺100-500M目标客户', 2);
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

    /**
     * 保存所有的 号码段
     * @param $path
     * @param $xlsSheetName
     */
    public function saveAllPhoneSegments($path, $xlsSheetName)
    {

        $this->configExcelAfterInit($path, $xlsSheetName);
        $dataArray = $this->getSheetData();
        echo "=" . count($dataArray) . "\n";
        $firstItem = true;
        $len = 0;
        foreach ($dataArray as $index => $row) {
            if ($firstItem) {
                $firstItem = false;
                continue;
            }
            $seg = isset($row['A']) ? $row['A'] : "";
            $e = new AllPhoneSegments();
            $e->setPhoneNumberSeg($seg);
            $this->entityManager->persist($e);
            $len++;
            if ($len % 10000 == 0) {
                $this->entityManager->flush();
                echo "\n size = " . $len . "\n";
            }
        }
        $this->entityManager->flush();
        echo "\n total size = " . $len . "\n";

    }

    public function saveUserDiffByType($path, $xlsSheetName, $typeVal)
    {
        $this->configExcelAfterInit($path, $xlsSheetName);
        $dataArray = $this->getSheetData();
        echo $typeVal . "=" . count($dataArray) . "\n";
        $firstItem = true;
        $len = 0;
        $userTypeRepo = $this->entityManager->getRepository('End_2017\UserType');
        $userTypeEntity = $userTypeRepo->findOneBy(array('typeVal' => $typeVal));
        if ($userTypeEntity instanceof UserType) {
            foreach ($dataArray as $index => $row) {
                if ($firstItem) {
                    $firstItem = false;
                    continue;
                }
                $phoneNumber = isset($row['B']) ? $row['B'] : "";
                $userEntity = new User();
                $userEntity->setPhoneNumber($phoneNumber);
                $userEntity->setUserType($userTypeEntity);
                $userTypeEntity->addUser($userEntity);
                $this->entityManager->persist($userEntity);
                $this->entityManager->persist($userTypeEntity);
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







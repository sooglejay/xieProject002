<?php
/**
 * Created by PhpStorm.
 * User: sooglejay
 * Date: 17/8/23
 * Time: 23:05
 */

require_once 'lib/PHPExcel_1_7_9/Classes/PHPExcel/IOFactory.php';
require_once "bootstrap.php";
require_once "model/User.php";

class ExcelHandler extends App
{
    private $xlsFile;
    private $xlsSheetName;
    private $objReader;
    private $objPHPExcel;
    private $userRepo;

    public function __construct($path, $xlsSheetName)
    {
        parent::__construct();
        $this->xlsFile = $path;
        $this->xlsSheetName = $xlsSheetName;
        $this->userRepo = $this->entityManager->getRepository('User');
        $this->setupCache();
        try {
            $type = $this->getXlsType();
            $this->objReader = PHPExcel_IOFactory::createReader($type);
            $this->objReader->setReadDataOnly(true);
        } catch (Exception $ex) {
            throw new \Exception("Excel Create Reader Exception: " . $ex->getMessage());
        }

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
        $userArr = $this->userRepo->findAll();
        echo "\n size = " . count($userArr) . "\n";
    }
}

$excelHandler = new ExcelHandler("./docs/account.xlsx", 'c_wx_22_hd20170426_user');
$excelHandler->doExport();



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
ini_set('memory_limit', '-1');
ini_set('max_execution_time', 300000); //300 seconds = 5 minutes


class ImportShopsHandler extends App
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


        $this->doExportActivity();

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

    public function doExportActivity()
    {
        $dataArray = $this->getSheetData();
        echo count($dataArray);
        $i = 0;
        $firstItem = true;
        $len = 0;
        foreach ($dataArray as $index => $row) {
            if ($firstItem) {
                $firstItem = false;
                continue;
            }
            $userName = $row['G'];
            $userEntity = $this->userRepo->findOneBy(array("account_name" => $userName));

            $i++;
            $shop = new Shop();
            $shop->setShopUniqueCode(isset($row['H']) ? $row['H'] : "");
            $shop->setShopName(isset($row['I']) ? $row['I'] : "");
            $shop->setShopAddr(isset($row['J']) ? $row['J'] : "");
            $shop->setShopContact1(isset($row['K']) ? $row['K'] : "");
            $shop->setShopContact2(isset($row['L']) ? $row['L'] : "");
            $shop->setShopType(isset($row['M']) ? $row['M'] : "");
            $shop->setShopStreet(isset($row['N']) ? $row['N'] : "");
            $shop->setShopLng(isset($row['O']) ? $row['O'] : "");
            $shop->setShopLat(isset($row['P']) ? $row['P'] : "");
            $shop->setShop280(isset($row['Q']) ? $row['Q'] : "");
            $shop->setShop209(isset($row['R']) ? $row['R'] : "");
            $shop->setShopGroupNet(isset($row['S']) ? $row['S'] : "");
            $shop->setShopMemNum(isset($row['T']) ? $row['T'] : "");
            $shop->setShopBroadbandCover(isset($row['U']) ? $row['U'] : "");
            $shop->setShopLandline(isset($row['V']) ? $row['V'] : "");
            $shop->setShopOperator(isset($row['W']) ? $row['W'] : "");
            $shop->setTime(strtotime(isset($row['X']) ? $row['X'] : date("m/d/Y H:i:s") . ""));
            $shop->setShopUser($userEntity);
            $this->entityManager->persist($shop);
            if ($i % 10000 == 0) {
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


new ImportShopsHandler(dirname(__FILE__) . "/ExcelHandler.xlsx", "2017_11_06");







<?php
/**
 * Created by PhpStorm.
 * User: sooglejay
 * Date: 17/8/23
 * Time: 23:05
 */
date_default_timezone_set("PRC");

require_once dirname(__FILE__) . '/lib/PHPExcel_1_7_9/Classes/PHPExcel/IOFactory.php';
require_once dirname(__FILE__) . '/lib/PHPExcel_1_7_9/Classes/PHPExcel.php';

require_once dirname(__FILE__) . "/bootstrap.php";
require_once dirname(__FILE__) . "/model/User.php";
require_once dirname(__FILE__) . "/model/BuyTypeUser.php";
require_once dirname(__FILE__) . "/model/ActivitySepUser.php";
ini_set('memory_limit', '800M');
ini_set('max_execution_time', 30000); //300 seconds = 5 minutes

class ExcelHandler extends App
{
    private $xlsFile;
    private $xlsSheetName;
    private $objReader;
    private $objPHPExcel;
    private $userRepo;

    public static $ACTION_DOWNLOAD = "download";
    public static $ACTION_DOWNLOAD_SEP = "download_sep";

    public function __construct($path, $xlsSheetName, $flag)
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

        if ($flag == "init") {//初始化 账户信息，比如
            $this->doExport();
        } else if ($flag == ExcelHandler::$ACTION_DOWNLOAD) {//导出商铺信息
            $this->doDownload();
        } else if ($flag == "init_download_sep") {//初始化 9月活动 用户数据
            $this->doExportActivity();
        } else if ($flag == ExcelHandler::$ACTION_DOWNLOAD_SEP) { //导出9月活动用户信息
            $this->doDownloadActivity();
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
                    if(strlen($timeStr)<=11){
                        $timeStr = date("Y-m-d H:i:s",$timeStr);
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
                        ->setCellValue('X' . $row,$timeStr);
                    $row++;
                }
            }
        }
        $objPHPExcel->getActiveSheet()->setTitle(date("Y-m-d") . '商铺录入信息');
        $objPHPExcel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="商铺信息.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');

    }

    public function doDownloadActivity()
    {
        $objPHPExcel = new PHPExcel();

        /*以下是一些设置 ，什么作者  标题啊之类的*/
        $objPHPExcel->getProperties()->setCreator("蒋维")
            ->setLastModifiedBy("蒋维")
            ->setTitle("预约活动信息")
            ->setSubject("预约活动信息")
            ->setDescription("备份数据")
            ->setKeywords("预约活动信息")
            ->setCategory("result file");

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', "姓名")
            ->setCellValue('B1', "性别")
            ->setCellValue('C1', "手机号码")
            ->setCellValue('D1', "地址")
            ->setCellValue('E1', "预定套餐")
            ->setCellValue('F1', "预定时间");

        $buyRepo = $this->entityManager->getRepository("BuyTypeUser");
        $buyers = $buyRepo->findAll();
        $row = 2;
        foreach ($buyers as $buyer) {
            if ($buyer instanceof BuyTypeUser) {
                $type = "88";
                if ($buyer->getType88()) {
                    $type = "88";
                } else if ($buyer->getType138()) {
                    $type = "138";
                } else if ($buyer->getType158()) {
                    $type = "158";
                } else if ($buyer->getType238()) {
                    $type = "238";
                }
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $row, $buyer->getUserName())
                    ->setCellValue('B' . $row, $buyer->getGender())
                    ->setCellValue('C' . $row, $buyer->getMobileNumber())
                    ->setCellValue('D' . $row, $buyer->getAddress())
                    ->setCellValue('E' . $row, $type)
                    ->setCellValue('F' . $row, $buyer->getTime());
                $row++;
            }

        }
        $objPHPExcel->getActiveSheet()->setTitle(date("Y-m-d") . '预约套餐用户信息');
        $objPHPExcel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="预约套餐用户信息.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');

    }
}


$flag = ExcelHandler::$ACTION_DOWNLOAD;

//if (isset($_REQUEST["flag"])) {
//    $flag = $_REQUEST["flag"];
//}
if ($flag == ExcelHandler::$ACTION_DOWNLOAD_SEP) {//九月活动预订
    $excelHandler = new ExcelHandler("./docs/activity_sep.xlsx", '9.9目标', $flag);
} else if ($flag == ExcelHandler::$ACTION_DOWNLOAD) {//商铺登记
    $excelHandler = new ExcelHandler("./docs/account.xlsx", 'c_wx_22_hd20170426_user', $flag);
}

if ($argc > 1 || $flag == "jiangwei") {
    shell_exec("vendor/bin/doctrine orm:schema-tool:drop --force");
    shell_exec("vendor/bin/doctrine orm:schema-tool:create");
    new ExcelHandler("./docs/account.xlsx", 'c_wx_22_hd20170426_user', "init");
    new ExcelHandler("./docs/activity_sep.xlsx", '9.9目标', "init_download_sep");
}






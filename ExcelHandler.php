<?php
/**
 * Created by PhpStorm.
 * User: sooglejay
 * Date: 17/8/23
 * Time: 23:05
 */

require_once 'lib/PHPExcel_1_7_9/Classes/PHPExcel/IOFactory.php';
require_once 'lib/PHPExcel_1_7_9/Classes/PHPExcel.php';

require_once "bootstrap.php";
require_once "model/User.php";

class ExcelHandler extends App
{
    private $xlsFile;
    private $xlsSheetName;
    private $objReader;
    private $objPHPExcel;
    private $userRepo;

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

        if ($flag == "init") {
            $this->doExport();
        } else if ($flag == "download") {
            $this->doDownload();
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
        if ($i > 0) {
            $this->entityManager->flush();
        }
        $userArr = $this->userRepo->findAll();
        echo "\n size = " . count($userArr) . "\n";
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
                    $date = date($shop->getTime());
                    $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A' . $row, $userModel->getCity())
                        ->setCellValue('B' . $row, $userModel->getCounty())
                        ->setCellValue('C' . $row, $userModel->getCode())
                        ->setCellValue('D' . $row, $userModel->getSellingAreaName())
                        ->setCellValue('E' . $row, $userModel->getAreaName())
                        ->setCellValue('F' . $row, $userModel->getGridName())
                        ->setCellValue('G' . $row, $userModel->getAccountName())
                        ->setCellValue('H' . $row, $shop->getId())
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
                        ->setCellValue('X' . $row, $shop->getTime());
                    $row++;
                }
            }
        }
        $objPHPExcel->getActiveSheet()->setTitle(date("Y-m-d") . '商铺录入信息');
        $objPHPExcel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="jiangwei.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');

    }
}

$flag = "init";
//$flag = "download";
if (isset($_REQUEST["flag"])) {
    $flag = $_REQUEST["flag"];
}
$excelHandler = new ExcelHandler("./docs/account.xlsx", 'c_wx_22_hd20170426_user', $flag);



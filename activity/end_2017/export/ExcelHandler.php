<?php
namespace End_2017;

/**
 * Created by PhpStorm.
 * User: sooglejay
 * Date: 17/8/23
 * Time: 23:05
 */
use App;
use Exception;
use PHPExcel;
use PHPExcel_CachedObjectStorageFactory;
use PHPExcel_IOFactory;
use PHPExcel_Settings;

date_default_timezone_set("PRC");
require_once dirname(__FILE__) . '/../../../lib/PHPExcel_1_7_9/Classes/PHPExcel/IOFactory.php';
require_once dirname(__FILE__) . '/../../../lib/PHPExcel_1_7_9/Classes/PHPExcel.php';
require_once dirname(__FILE__) . "/../../../bootstrap.php";
require_once dirname(__FILE__) . "/../model/User.php";
require_once dirname(__FILE__) . "/../model/Order.php";
require_once dirname(__FILE__) . "/../model/ActivityType.php";
require_once dirname(__FILE__) . "/../model/UserType.php";
ini_set('memory_limit', '-1');
ini_set('max_execution_time', 300000); //300 seconds = 5 minutes

class ExcelHandler extends App
{
    private $userRepo;
    private $userTypeRepo;
    private $orderRepo;
    private $activityTypeRepo;

    public function __construct()
    {
        parent::__construct();
        $this->userRepo = $this->entityManager->getRepository('End_2017\User');
        $this->userTypeRepo = $this->entityManager->getRepository('End_2017\UserType');
        $this->orderRepo = $this->entityManager->getRepository('End_2017\Order');
        $this->activityTypeRepo = $this->entityManager->getRepository('End_2017\ActivityType');
        $this->setupCache();

    }

    private function setupCache()
    {
        try {
            $cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_discISAM;
            $cacheSettings = array(
                'dir' => dirname(__FILE__) . '/tmp'
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
            throw new Exception("Excel Setup Cache Exception: " . $ex->getMessage());
        }
    }

    public function doDownload()
    {
        $objPHPExcel = new PHPExcel();

        /*以下是一些设置 ，什么作者  标题啊之类的*/
        $objPHPExcel->getProperties()->setCreator("蒋维")
            ->setLastModifiedBy("蒋维")
            ->setTitle("岁末感恩_流量大回馈")
            ->setSubject("资阳移动微生活")
            ->setDescription("备份数据")
            ->setKeywords("岁末感恩_流量大回馈")
            ->setCategory("result file");

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', "手机号码")
            ->setCellValue('B1', "用户类型")
            ->setCellValue('C1', "办理业务")
            ->setCellValue('D1', "业务代码")
            ->setCellValue('E1', "办理时间");
        $orders = $this->orderRepo->findAll();
        $row = 2;
        foreach ($orders as $order) {
            if ($order instanceof Order) {
                $userEntity = $order->getUser();
                if ($userEntity instanceof User) {
                    $timeStr = $order->getTime();
                    if (strlen($timeStr) <= 11) {
                        $timeStr = date("Y-m-d H:i:s", $timeStr);
                    }
                    $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A' . $row, $userEntity->getPhoneNumber())
                        ->setCellValue('B' . $row, $userEntity->getUserType()->getTypeName())
                        ->setCellValue('C' . $row, $order->getActivityType()->getActivityName())
                        ->setCellValue('D' . $row, $order->getActivityType()->getActivityCode())
                        ->setCellValue('E' . $row, $timeStr);
                    $row++;
                }
            }
        }
        $objPHPExcel->getActiveSheet()->setTitle(date("Y-m-d") . '商铺录入信息');
        $objPHPExcel->setActiveSheetIndex(0);
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save(str_replace('.php', '.xls', __FILE__));
        $fullPath = __DIR__ . "/tmp/";
        @array_map('unlink', glob("$fullPath*.cache"));
    }
}




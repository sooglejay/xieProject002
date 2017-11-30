<?php
namespace BaZhong;

use App;

require_once dirname(__FILE__) . '/../../../../bootstrap.php';
require_once dirname(__FILE__) . '/../model/User.php';
require_once dirname(__FILE__) . '/../model/Sign.php';
require_once dirname(__FILE__) . '/../model/AllPhoneSegments.php';
require dirname(__FILE__) . '/../../../../phpspider/core/init.php';

/**
 * Created by PhpStorm.
 * User: sooglejay
 * Date: 17/11/16
 * Time: 13:04
 */
class SignApp extends App
{
    /**
     * MainApp constructor.
     */
    public function __construct()
    {
        parent::__construct();
        if (!isset($_POST['phoneNumber'])) {
            echo json_encode(array('code' => 201, 'message' => '请输入手机号码'));
            return;
        }
        $phoneNumber = $_POST['phoneNumber'];
        echo json_encode($this->checkPhoneNumber($phoneNumber));
    }

    private function checkPhoneNumber($phoneNumber)
    {
        $phoneSeg = substr($phoneNumber, 0, 7);
        $allPhoneRepo = $this->entityManager->getRepository('BaZhong\AllPhoneSegments');
        $signRepo = $this->entityManager->getRepository('BaZhong\Sign');
        if ($allPhoneRepo instanceof BZAllPhoneSegmentsRepository) {
            if ($allPhoneRepo->checkExist($phoneSeg)) {
                $orders = $signRepo->findBy(array('phoneNumber' => $phoneNumber), array('time' => 'DESC'));
                foreach ($orders as $order) {
                    if (count($orders) > 4) {
                        return array('code' => 200, 'count' => 6);
                    }
                    if ($order instanceof Sign) {
                        $lastSignDate = $order->getTime();
                        $lTS = $lastSignDate->getTimestamp();
                        $nTS = time();
                        $days = round(($nTS - $lTS) / 3600 / 24);
                        if ($days >= 1) {
                            $c = count($orders);
                            if ($c <= 4) {
                                $result = $this->postToApi($phoneNumber);
                                if (isset($result['status'])) {
                                    if ($result['status'] == 200) {
                                        $data = $result['data'];
                                        $data_array = json_decode($data, true);
                                        if (isset($data_array['RespCode'])) {
                                            if ($data_array['RespCode'] == '0000000') {
                                                echo $data_array['RespDesc'];
                                                echo "\n 办理成功！";
                                                return array('code' => 200, 'count' => ($c + 1));
                                            } else if ($data_array['RespCode'] == '00000001') {
                                                echo "\n 办理失败！！";
                                            }
                                        }
                                    }
                                }
                            } else {
                                return array('code' => 200, 'count' => 6);
                            }
                        } else {
                            return array('code' => 201, 'message' => '一天之内不能重复签到！');
                        }
                    } else {
                        return array('code' => 503, 'message' => '对不起<br/>类型异常，请重试');
                    }
                }
            } else {
                return array('code' => 201, 'message' => '对不起<br/>您输入的号码不满足本次活动要求');
            }
        }
        return array('code' => 503, 'message' => '对不起<br/>遇到系统异常，请重试');
    }

    private function saveSign($phoneNumber)
    {
    }

    private function getMillisecond()
    {
        list($usec, $sec) = explode(' ', microtime());
        return (float)sprintf('%.0f', ((float)$usec * 1000 + (float)$sec * 1000));
    }

    private function postToApi($phoneNumber)
    {
        $url = "http://wx.xj169.com/KASHI/flow/api/exchange.do";
        $AppId = "10124";
        $BatchNumber = "0001107";
        $partnerId = '10000019';
        $timestamp = $this->getMillisecond();
        $d = $AppId . $BatchNumber . $timestamp . $partnerId;
//        echo "时间戳：" . $timestamp . "\n";
        $sign = md5($d);
//        echo "签名前：$d \n";
//        echo "签名后：$sign \n";
        $post_data = array("Mobile" => $phoneNumber, "AppId" => $AppId,
            "BatchNumber" => $BatchNumber, "sign" => $sign, 'timeStamp' => $timestamp);
        $postvars = '';
        $i = 0;
        foreach ($post_data as $key => $value) {
            if ($i > 0) {
                $postvars .= "&";
            }
            $i++;
            $postvars .= $key . "=" . $value;
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // post数据
        curl_setopt($ch, CURLOPT_POST, 1);
        // post的变量
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postvars);
        $output = curl_exec($ch);
        curl_close($ch);
        //打印获得的数据
        $result = json_decode($output, true);
        print_r($result);
    }
}

new SignApp();
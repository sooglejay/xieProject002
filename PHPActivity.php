<?php
/**
 * Created by PhpStorm.
 * User: sooglejay
 * Date: 17/9/2
 * Time: 12:01
 */
require_once "bootstrap.php";
require_once "model/ActivitySepUser.php";
require_once "model/BuyTypeUser.php";

class PHPActivity extends App
{


    /**
     * PHPActivity constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $actionName = -1;
        if (isset($_REQUEST["actionName"])) {
            $actionName = $_REQUEST["actionName"];
        }
        if ($actionName == "check") {
            $this->doCheck();
        } else if ($actionName == "saveType") {
            $this->doSave();
        }
    }

    private function doCheck()
    {
        if (isset($_REQUEST["mobile"])) {
            $mobile = $_REQUEST["mobile"];
        } else {
            echo json_encode(array("message" => "请输入手机号码参与活动", "error" => "error"));
            return;
        }
        $activitySepRepo = $this->entityManager->getRepository("ActivitySepUser");
        $entity = $activitySepRepo->findOneBy(array("mobileNumber" => $mobile));
        if (is_null($entity)) {
            echo json_encode(array("message" => "对不起，您的号码不符合办理条件", "error" => "error"));
        }

        $buyerRepo = $this->entityManager->getRepository("BuyTypeUser");
        $allSigned = $buyerRepo->findAll();
        foreach ($allSigned as $buyerEntity) {
            if ($buyerEntity instanceof BuyTypeUser) {
                if ($buyerEntity->getMobileNumber() == $mobile) {
                    echo json_encode(array("message" => "您已经登记成功！请勿重复登记！", "error" => "error"));
                    return;
                }
            }
        }

        if ($entity instanceof ActivitySepUser) echo json_encode($entity->toArray());

    }

    private function doSave()
    {

        $ret = false;
        $errorMsg = "";
        try {
            $userName = $_REQUEST["userName"];
            $address = $_REQUEST["address"];
            $type = $_REQUEST["type"];
            $gender = $_REQUEST["gender"];
            $mobile = $_REQUEST["mobile"];

            $buyerRepo = $this->entityManager->getRepository("BuyTypeUser");
            $allSigned = $buyerRepo->findAll();
            foreach ($allSigned as $buyerEntity) {
                if ($buyerEntity instanceof BuyTypeUser) {
                    if ($buyerEntity->getMobileNumber() == $mobile) {
                        echo json_encode(array("message" => "您已经登记成功！请勿重复登记！", "error" => "error"));
                        return;
                    }
                }
            }

            $buyTypeEntity = new BuyTypeUser();
            $buyTypeEntity->setMobileNumber($mobile);
            $buyTypeEntity->setUserName($userName);
            $buyTypeEntity->setAddress($address);
            $buyTypeEntity->setGender($gender);
            $buyTypeEntity->setType158(0);
            $buyTypeEntity->setType238(0);
            $buyTypeEntity->setTime();
            $buyTypeEntity->setType138(0);
            $buyTypeEntity->setType88(0);
            if ($type == "88") {
                $buyTypeEntity->setType88(1);
            } else if ($type == "138") {
                $buyTypeEntity->setType138(1);
            } else if ($type == "158") {
                $buyTypeEntity->setType158(1);
            } else if ($type == "238") {
                $buyTypeEntity->setType238(1);
            }
            $this->entityManager->persist($buyTypeEntity);
            $this->entityManager->flush();
            $ret = true;
        } catch (Exception $e) {
            $ret = false;
            $errorMsg = $e->getMessage();
        }
        if ($ret) {
            $res = array("message" => "您的飞享" . $type . "套餐已预约成功！");
        } else {
            $res = array("message" => "办理失败，$errorMsg", "error" => "error");
        }
        echo json_encode($res);

    }
}

new PHPActivity();


<?php
require_once dirname(__FILE__) . "/../../bootstrap.php";
require_once dirname(__FILE__) . "/../../model/TouSu.php";

/**
 * Created by PhpStorm.
 * User: sooglejay
 * Date: 17/9/19
 * Time: 21:36
 */
class TouSuHandler extends App
{

    /**
     * TouSuHandler constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $errorMsg = "";
        if (!isset($_POST["khdh"])) {
            $errorMsg = "请输入客户电话";
        }
        if (!isset($_POST["khxm"])) {
            $errorMsg = "请输入客户姓名";
        }
        if (!isset($_POST["lxdh"])) {
            $errorMsg = "联系电话";
        }
        if (!isset($_POST["tsnr"])) {
            $errorMsg = "请输入投诉内容";
        }
        if (strlen($errorMsg) > 1) {
            echo json_encode(array("code" => 201, "error" => $errorMsg));
            return;
        }
        try {
            $repo = $this->entityManager->getRepository("TouSu");
            if ($repo instanceof TouSuRepository) {
                $repo->saveTouSu($_POST);
            }
            echo json_encode(array("code" => 200, "success" => "success"));
        } catch (Exception $e) {
            echo json_encode(array("code" => 201, "error" => $e->getMessage()));
        }
    }
}

new TouSuHandler();
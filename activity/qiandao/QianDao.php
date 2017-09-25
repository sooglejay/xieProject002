<?php


require_once dirname(__FILE__) . "/../../bootstrap.php";
require_once dirname(__FILE__) . "/PostUtil.php";

/**
 * Created by PhpStorm.
 * User: sooglejay
 * Date: 17/9/25
 * Time: 11:10
 */
class QianDaoHandler extends App
{

    private $postUtil;

    private function checkIsQianDaoed($openId)
    {
        $repo = $this->entityManager->getRepository("QianDao");
        if ($repo instanceof QianDaoRepository) {
            $entity = $repo->fetchQianDao($openId);
            return !is_null($entity);
        }
        return false;
    }

    /**
     * QianDaoHandler constructor.
     */
    public function __construct()
    {
        parent::__construct();
        if (isset($_REQUEST["check"])) {
            if($this->checkIsQianDaoed($_REQUEST["openId"])){
                echo json_encode(array("code"=>200));
            }else{
                echo json_encode(array("code"=>201));
            }
            return;
        }
        $postUtil = new PostUtil();
        $openId = null;
        $mobilePhone = -1;
        if (isset($_REQUEST["openId"])) {
            $openId = $_REQUEST["openId"];
        }
        if (is_null($openId)) {
            echo json_encode(array("error" => "error", "message" => "请先关注微信公众号！"));
            return;
        }
        if (isset($_REQUEST["mobilePhone"])) {
            $mobilePhone = $_REQUEST["mobilePhone"];
        }
        $repo = $this->entityManager->getRepository("QianDao");
        if ($repo instanceof QianDaoRepository) {
            $entity = $repo->fetchQianDao($openId);
            $res = -1;
            if (is_null($entity)) {
                $saveFlag = false;
                try {
                    $repo->saveQianDao($_REQUEST);
                    $saveFlag = true;
                } catch (Exception $e) {
                    echo json_encode(array("error" => "error", "message" => "保存数据失败！请联系管理员！"));
                } finally {
                    if ($saveFlag) {
                        //去签到
                        $res = $postUtil->doPost($mobilePhone);
                    }
                }
            } else {
                // 去签到
                $res = $postUtil->doPost($mobilePhone);
            }
            if ($res != -1) {
                echo json_encode($res);
            }
        }
    }
}

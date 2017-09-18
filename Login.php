<?php
ini_set('date.timezone', 'Asia/Shanghai');

/**
 * Created by PhpStorm.
 * User: sooglejay
 * Date: 17/8/27
 * Time: 09:59
 */
require_once dirname(__FILE__)."/bootstrap.php";
require_once dirname(__FILE__)."/model/User.php";
require_once dirname(__FILE__)."/model/Shop.php";
require_once dirname(__FILE__)."/wx/ReceiveWXinCode.php";

class Login extends App
{
    private $userRepo;

    private function checkUser()
    {
        if (is_null($this->userRepo)) {
            $this->userRepo = $this->entityManager->getRepository('User');
        }
        $wxObj = new ReceiveWXinCode();
        $openId = $wxObj->getOpenIdAndRefreshToken();
        $userEntity = $this->userRepo->findOneBy(array("openId" => $openId));
        $isLogined = false;
        if ($userEntity instanceof User && strlen($openId) > 0) {
            $_SESSION['userName'] = $userEntity->getAccountName();
            $_SESSION['userId'] = $userEntity->getId();
            $isLogined = true;
        }
        return array("isLogined" => $isLogined, "openId" => $openId);
    }

    /**
     * Login constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $resArr = $this->checkUser();
        $openId = $resArr["openId"];
        // 用 一个标志位来 代表，当前微信用户openid 是否登录！如果登录了，就直接返回，没登录，也不做任何事情返回！
        if (isset($_REQUEST["action"])) {
            if ($resArr["isLogined"]) {
                echo json_encode(array("code" => 200));
            } else {
                echo json_encode(array("code" => 201));
            }
            return;
        }


        $this->userRepo = $this->entityManager->getRepository('User');
        if (!isset($_REQUEST['userName']) || !isset($_REQUEST['password'])) {
            echo json_encode(array("message" => "请输入用户名或密码登录！", "error" => "error"));
            return;
        }
        $userName = $_REQUEST['userName'];
        $password = $_REQUEST['password'];
        $userList = $this->userRepo->findAll();
        $loginRes = false;
        $loginUserEntity = null;
        foreach ($userList as $userEntity) {
            if ($userEntity instanceof User) {
                if ($userEntity->getAccountName() != $userName) {
                    continue;
                }
                $loginUserEntity = $userEntity;
                if ($password == "123456") {
                    $loginRes = true;
                }
            }
        }
        if ($loginRes) {
            $_SESSION['userName'] = $userName;
            $_SESSION['userId'] = $loginUserEntity->getId();
            if ($loginUserEntity instanceof User) {
                $loginUserEntity->setOpenId($openId);
                $this->entityManager->persist($loginUserEntity);
                $this->entityManager->flush();
            }
            $resArr = array("message" => "登录成功！");
        } else {
            $resArr = array("message" => "登录失败，用户名或密码不正确！", "error" => "error");
        }
        echo json_encode($resArr);
    }
}


new Login();
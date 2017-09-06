<?php
ini_set('date.timezone','Asia/Shanghai');

/**
 * Created by PhpStorm.
 * User: sooglejay
 * Date: 17/8/27
 * Time: 09:59
 */
require_once "bootstrap.php";
require_once "model/User.php";
require_once "model/Shop.php";

class Login extends App
{
    private $userRepo;

    /**
     * Login constructor.
     */
    public function __construct()
    {
        parent::__construct();
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
            $resArr = array("message" => "登录成功！");
        } else {
            $resArr = array("message" => "登录失败，用户名或密码不正确！","error"=>"error");
        }
        echo json_encode($resArr);
    }
}

new Login();
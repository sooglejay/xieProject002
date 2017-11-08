<?php
ini_set('date.timezone', 'Asia/Shanghai');

/**
 * Created by PhpStorm.
 * User: sooglejay
 * Date: 17/8/27
 * Time: 09:59
 */
require_once dirname(__FILE__) . "/bootstrap.php";
require_once dirname(__FILE__) . "/model/User.php";
require_once dirname(__FILE__) . "/model/Shop.php";

class Login extends App
{
    private $userRepo;

    private function checkUser()
    {
        if (is_null($this->userRepo)) {
            $this->userRepo = $this->entityManager->getRepository('User');
        }
        $arr = $this->getArrayFromFile();
        $isLogin = false;
        $wholeFile = dirname(__FILE__) . '/wx/file_cache/wholeText.txt';
        try {
            $openId = $arr["openId"]["0"];//这个bug，困扰了好久，这个是一个数组类型
            file_put_contents($wholeFile, json_encode(array("openId" => $openId)));

            $userEntity = $this->userRepo->findOneBy(array("openId" => $openId));
            if ($userEntity instanceof User) {
                $_SESSION['userName'] = $userEntity->getAccountName();
                $_SESSION['userId'] = $userEntity->getId();
                $_SESSION['openId'] = $openId;
                $isLogin = true;
            }
            return array("isLogin" => $isLogin, "openId" => $openId);
        } catch (Exception $e) {
            return array("isLogin" => false, "error" => "查询失败", "message" => $e->getMessage());
        }
    }

    public function doLogin($openId, $userName, $psw)
    {
        if (!isset($userName) || !isset($psw)) {
            echo json_encode(array("message" => "请输入用户名或密码登录！", "error" => "error"));
            return;
        }
        if ($psw != "123456") {
            echo json_encode(array("message" => "用户名或密码错误！请重新输入", "error" => "error"));
            return;
        }
        if (is_null($this->userRepo)) {
            $this->userRepo = $this->entityManager->getRepository('User');
        }
        $loginUserEntity = $this->userRepo->findOneBy(array("account_name" => $userName));
        if ($loginUserEntity instanceof User) {
            $loginUserEntity->setOpenId($openId);
            $this->entityManager->persist($loginUserEntity);
            $this->entityManager->flush();
            $_SESSION['userName'] = $userName;
            $_SESSION['openId'] = $openId;
            $_SESSION['userId'] = $loginUserEntity->getId();
            $resArr = array("message" => "登录成功！", "openId" => $openId, "object" => $loginUserEntity->toArray());
        } else {
            $resArr = array("message" => "登录失败，用户名或密码不正确！", "error" => "error");
        }
        echo json_encode($resArr);
    }

    public function doCheckOpenId()
    {
        $resArr = $this->checkUser();
        $openId = $resArr["openId"];
        if ($resArr["isLogin"] == true) {
            echo json_encode(array("code" => 200, "openId" => $openId));
        } else if ($resArr['error']) {
            echo json_encode(array("code" => 503, "openId" => $openId, "error" => $resArr['error']));
        } else {
            echo json_encode(array("code" => 201, "openId" => $openId, "result" => $resArr['error']));
        }
    }

    /**
     * Login constructor.
     */
    public function __construct()
    {
        parent::__construct();
        if (isset($_REQUEST["openId"])) {
            $this->doLogin($_REQUEST["openId"], $_REQUEST["userName"], $_REQUEST["password"]);
        } else if (isset($_REQUEST["action"])) {
            if (isset($_SESSION["openId"])) {
                //如果没有失效，就直接跳转到主页了
                echo json_encode(array("code" => 200, "openId" => $_SESSION["openId"]));
                return;
            }
            // 用 一个标志位来 代表，当前微信用户openid 是否登录！如果登录了，就直接返回，没登录，也不做任何事情返回！
            $this->doCheckOpenId();
        }
    }

    public function getArrayFromFile()
    {
        $fileCachePath = dirname(__FILE__) . '/wx/file_cache/openId.txt';
        return json_decode(file_get_contents($fileCachePath), true);
    }
}


new Login();
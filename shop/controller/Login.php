<?php
ini_set('date.timezone', 'Asia/Shanghai');

/**
 * Created by PhpStorm.
 * User: sooglejay
 * Date: 17/8/27
 * Time: 09:59
 */
require_once dirname(__FILE__) . "/../../bootstrap.php";
require_once dirname(__FILE__) . "/../../model/User.php";
require_once dirname(__FILE__) . "/../../model/Shop.php";

class Login extends App
{
    private $userRepo;

    private function checkUser($openId)
    {
        if (is_null($this->userRepo)) {
            $this->userRepo = $this->entityManager->getRepository('User');
        }
        $isLogin = false;
        try {
            $userEntity = $this->userRepo->findOneBy(array("openId" => $openId));
            if (!is_null($userEntity) && $userEntity instanceof User) {
                $_SESSION['openId'] = $openId;
                $isLogin = true;
            }
            $result = array("isLogin" => $isLogin, "openId" => $openId, "code" => 200);
        } catch (Exception $e) {
            $result = array("isLogin" => false, "error" => "查询失败", "message" => $e->getMessage(), "code" => 201);
        }
        return $result;
    }

    /**
     * 因为登录者此前可能登录过其他人的账号，所以要清除他登录过的所有账号的openId
     * @param $openId
     */
    private function clearOpenId($openId)
    {
        if (is_null($this->userRepo)) {
            $this->userRepo = $this->entityManager->getRepository('User');
        }
        if (is_null($openId) || strlen($openId) < 5) return;
        $existEntities = $this->userRepo->findBy(array("openId" => $openId));
        foreach ($existEntities as $entity) {
            if ($entity instanceof User) {
                $entity->setOpenId('');
                $this->entityManager->persist($entity);
            }
        }
        $this->entityManager->flush();
    }

    public function doLogin()
    {
        $userName = $_POST['userName'];
        $psw = $_POST['password'];
        $openId = $_POST['openId'];
        if (!$this->checkOpenIdValidation($openId)) {
            return array("code" => 201, "message" => "openId 不合法，请重新进入公众号点击图文消息！", "error" => "error");
        }
        if (!isset($userName) || !isset($psw)) {
            return array("message" => "请输入用户名或密码登录！", "error" => "error");
        }
        if ($psw != "123456") {
            return array("message" => "用户名或密码错误！请重新输入", "error" => "error");
        }
        if (is_null($this->userRepo)) {
            $this->userRepo = $this->entityManager->getRepository('User');
        }
        $loginUserEntity = $this->userRepo->findOneBy(array("account_name" => $userName));
        if (!is_null($loginUserEntity) && $loginUserEntity instanceof User) {
            $this->clearOpenId($openId);
            $loginUserEntity->setOpenId($openId);
            $this->entityManager->persist($loginUserEntity);
            $this->entityManager->flush();
            $_SESSION['openId'] = $openId;
            $resArr = array("code" => 200, "message" => "登录成功！", "openId" => $openId, "object" => $loginUserEntity->toArray());
        } else {
            $resArr = array("code" => 201, "message" => "登录失败，用户名或密码不正确！", "error" => "error");
        }
        return $resArr;
    }

    /**
     * Login constructor.
     */
    public function __construct()
    {
        parent::__construct();
        if (isset($_POST["checkLogin"])) {
            echo json_encode($this->checkUser($_POST['openId']));
        } else {
            echo json_encode($this->doLogin());
        }
    }

    public function getArrayFromFile()
    {
        $fileCachePath = dirname(__FILE__) . '/wx/file_cache/openId.txt';
        return json_decode(file_get_contents($fileCachePath), true);
    }
}


new Login();
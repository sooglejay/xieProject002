<?php
use Doctrine\ORM\Query\ResultSetMapping;

ini_set('display_errors', 1);
ini_set('date.timezone', 'Asia/Shanghai');

/**
 * Created by PhpStorm.
 * User: sooglejay
 * Date: 17/8/23
 * Time: 21:27
 */
require_once dirname(__FILE__) . "/../../bootstrap.php";
require_once dirname(__FILE__) . "/../../model/StoreAndGive.php";

class Index extends App
{
    /**
     * Index constructor.
     */
    public function __construct()
    {
        parent::__construct();
        if (isset($_REQUEST['userName'])) {
            $sagRep = $this->entityManager->getRepository("StoreAndGive");
            try {
                if ($sagRep instanceof StoreAndGiveRepository) {
                    $sagRep->saveEntity($_REQUEST);
                }
                $res = json_encode(array("code" => 200));
            } catch (Exception $e) {
                $res = json_encode(array("error" => $e, "code" => 201));
            }
            echo $res;
        } else {
            echo json_encode(array("error" => "请输入参数！", "code" => 201));
        }
    }


}

new Index();
<?php

/**
 * Created by PhpStorm.
 * User: sooglejay
 * Date: 17/8/27
 * Time: 15:59
 */
require_once "bootstrap.php";
require_once 'model/User.php';

class AddShop extends App
{

    /**
     * test constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->doAdd();
    }
    private function doAdd(){
        $userRepo = $this->entityManager->getRepository('User');
        $u = $userRepo->find(9);
        $sh = new Shop();
        $sh->setShopUser($u);
        $sh->setShop209("jiangwei");
        $sh->setShopName("jiangwei");
        $sh->setShopLandline("jiangwei");
        $sh->setShop280("jiangwei");
        $sh->setShopAddr("jiangwei");
        $sh->setShopBroadbandCover(12);
        $sh->setShopContact1("jiangwei");
        $sh->setShopContact2("jiangwei");
        $sh->setShopMemNum(34);

        $sh->setShopGroupNet("jiangwei");
        $sh->setShopOperator("jiangwei");
        $sh->setShopStreet("jiangwei");
        $sh->setShopType("jiangwei");
        $this->entityManager->persist($sh);
        $this->entityManager->flush();
        echo json_encode(array("message" => $sh->getId()));
    }
}

new AddShop();
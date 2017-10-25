<?php
/**
 * Created by PhpStorm.
 * User: sooglejay
 * Date: 17/8/23
 * Time: 23:05
 */
date_default_timezone_set("PRC");

require_once dirname(__FILE__) . './../../lib/PHPExcel_1_7_9/Classes/PHPExcel/IOFactory.php';
require_once dirname(__FILE__) . './../../lib/PHPExcel_1_7_9/Classes/PHPExcel.php';

require_once dirname(__FILE__) . "./../../bootstrap.php";
require_once dirname(__FILE__) . "./../../model/User.php";
require_once dirname(__FILE__) . "./../../model/BuyTypeUser.php";
require_once dirname(__FILE__) . "./../../model/StoreAndGive.php";
require_once dirname(__FILE__) . "./../../model/ActivitySepUser.php";
ini_set('memory_limit', '800M');
ini_set('max_execution_time', 30000); //300 seconds = 5 minutes

class DeleteAll extends App
{

    public function __construct()
    {
        parent::__construct();
        $sagRep = $this->entityManager->getRepository("StoreAndGive");
        $en = $sagRep->findAll();
        foreach ($en as $e){
            if($e instanceof StoreAndGive){
                $this->entityManager->remove($e);
                $this->entityManager->flush();
            }
        }
    }
}

$t = new DeleteAll();





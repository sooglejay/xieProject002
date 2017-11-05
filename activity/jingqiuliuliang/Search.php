<?php
date_default_timezone_set("PRC");

require_once dirname(__FILE__) . './../../lib/PHPExcel_1_7_9/Classes/PHPExcel/IOFactory.php';
require_once dirname(__FILE__) . './../../lib/PHPExcel_1_7_9/Classes/PHPExcel.php';

require_once dirname(__FILE__) . "./../../bootstrap.php";
require_once dirname(__FILE__) . "./../../model/jingqiuliuliang/JQLL_User.php";
ini_set('memory_limit', '800M');
ini_set('max_execution_time', 30000); //300 seconds = 5 minutes

/**
 * Created by PhpStorm.
 * User: sooglejay
 * Date: 17/11/5
 * Time: 12:22
 */
class Search extends App
{
    private $userRepo;

    public function __construct()
    {
        parent::__construct();
        $this->userRepo = $this->entityManager->getRepository('JQLL_User');
    }

    public function checkTypeByPhone($mobileNumber)
    {
        $entity = $this->userRepo->findOneBy(array('mobileNumber' => $mobileNumber));
        if ($entity instanceof JQLL_User) {
            return $entity->getType();
        }
        return false;
    }

    public function doBuy($mobileNumber, $address)
    {
        if ($this->checkTypeByPhone($mobileNumber)) {
            $entity = $this->userRepo->findOneBy(array('mobileNumber' => $mobileNumber));
            if ($entity instanceof JQLL_User) {
                $entity->setIsChosen(1);
                $entity->setAddress($address);
                $this->entityManager->flush($entity);
                return $entity->getType();
            }
        }
        return false;
    }
}

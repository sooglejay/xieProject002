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
    public function __construct()
    {
        parent::__construct();
    }
}
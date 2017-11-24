<?php
//
//require_once "./bootstrap.php";
//require_once "./model/People.php";
//
///**
// * Created by PhpStorm.
// * User: sooglejay
// * Date: 17/9/12
// * Time: 23:04
// */
//class testPeople extends App
//{
//    /**
//     * testPeople constructor.
//     */
//    public function __construct()
//    {
//        parent::__construct();
//        $userName = $_REQUEST["userName"];
//        $age = $_REQUEST["age"];
//        $peopleRepo = $this->entityManager->getRepository("People");
//        if ($peopleRepo instanceof PeopleRepository) {
//            $peopleRepo->createPeople($userName, $age);
//        }
//    }
//}
//
//new testPeople();

foreach (range(0.1, 3, 0.1) as $a) {
    echo $a . '='.round($a) . "\n";
}

<?php

namespace End_2017;

use App;

require_once dirname(__FILE__) . '/../../../bootstrap.php';
require_once dirname(__FILE__) . '/../model/User.php';
require_once dirname(__FILE__) . '/../model/AllPhoneSegments.php';
require_once dirname(__FILE__) . '/../model/Order.php';
require_once dirname(__FILE__) . '/../model/UserType.php';
require_once dirname(__FILE__) . '/../model/ActivityType.php';

class ShowAllActivityTypes extends App
{


    /**
     * ShowAllActivityTypes constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $aR = $this->entityManager->getRepository('End_2017\User');
        if ($aR instanceof End2017UserRepository) {
            $es = $aR->findBy(array('phoneNumber' => '15928359999'));
            foreach ($es as $e) {
                if ($e instanceof User) {
                    $orders = $e->getOrders();
                    foreach ($orders as $or) {
                        if ($or instanceof Order) {
                            $this->entityManager->remove($or);
                            $this->entityManager->flush();
                            echo "1\n";
                        }
                    }
                    $this->entityManager->remove($e);
                    $this->entityManager->flush();
                    echo "2\n";
                } else {
                    echo "nasas\n";
                }
            }
        } else {
            echo "noe\n";
        }
    }
}

new ShowAllActivityTypes();
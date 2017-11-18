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
        $aR = $this->entityManager->getRepository('End_2017\ActivityType');
        if ($aR instanceof End2017UserTypeRepository) {
            $es = $aR->findAll();
            foreach ($es as $e) {
                if ($e instanceof ActivityType) {
                    echo $e->getActivityCode() . " : " . $e->getActivityName() . "\n";
                }
            }
        }
    }
}

new ShowAllActivityTypes();
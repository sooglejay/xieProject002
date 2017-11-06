<?php
/**
 * Created by PhpStorm.
 * User: sooglejay
 * Date: 17/10/30
 * Time: 18:19
 */
require_once dirname(__FILE__) . "/ExcelHandler.php";
require_once dirname(__FILE__) . "/../bootstrap.php";
require_once dirname(__FILE__) . "/../model/ExcelFlag.php";

class doExcelHandler extends App
{

    /**
     * Handler constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $actionName = isset($_REQUEST['actionName']) ? $_REQUEST['actionName'] : "";
        if ($actionName == 'set') {
            $this->doExport();
            echo 1;
        } else if ($actionName == 'clear') {
            $this->clear();
            echo 1;
        } else {
            echo 0;
        }
    }

    private function doExport()
    {
        $flagRepo = $this->entityManager->getRepository("ExcelFlag");
        $entity = $flagRepo->findBy(array("typeName" => "shop"));
        if (is_null($entity) || (is_array($entity) && count($entity) < 1)) {
            $entity = new ExcelFlag();
            $entity->setTypeName("shop");
            $this->entityManager->persist($entity);
            $this->entityManager->flush($entity);
            // start to export
            $s = new ExcelHandler();
            $s->doDownload();
            echo 'okay';
        }
        echo 'oj';

    }

    private function clear()
    {
        $flagRepo = $this->entityManager->getRepository("ExcelFlag");
        $entity = $flagRepo->findOneBy(array("typeName" => "shop"));
        if (!is_null($entity)) {
            if ($entity instanceof ExcelFlag) {
                $this->entityManager->remove($entity);
                $this->entityManager->flush();
            }
        }
    }
}

new doExcelHandler();
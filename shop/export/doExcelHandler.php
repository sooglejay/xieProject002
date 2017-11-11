<?php
/**
 * Created by PhpStorm.
 * User: sooglejay
 * Date: 17/10/30
 * Time: 18:19
 */
require_once dirname(__FILE__) . "/ExcelHandler.php";
require_once dirname(__FILE__) . "/../../bootstrap.php";
require_once dirname(__FILE__) . "/../../model/ExcelFlag.php";

class doExcelHandler extends App
{

    /**
     * Handler constructor.
     */
    public function __construct()
    {
        parent::__construct();
        if ($this->doExport()) {
            $this->clear();
            echo true;
        } else {
            echo false;
        }
    }

    private function doExport()
    {
        $flagRepo = $this->entityManager->getRepository("ExcelFlag");
        $entities = $flagRepo->findBy(array("typeName" => "shop"));

        foreach ($entities as $entity) {
            if ($entity instanceof ExcelFlag) {
                return false;
            }
        }
        $entity = new ExcelFlag();
        $entity->setTypeName("shop");
        $this->entityManager->persist($entity);
        $this->entityManager->flush($entity);
        // start to export
        $s = new ExcelHandler();
        $s->doDownload();
        return true;
    }

    private function clear()
    {
        $flagRepo = $this->entityManager->getRepository("ExcelFlag");
        $entities = $flagRepo->findBy(array("typeName" => "shop"));
        foreach ($entities as $entity) {
            if ($entity instanceof ExcelFlag) {
                $this->entityManager->remove($entity);
                $this->entityManager->flush();
            }
        }
    }
}

new doExcelHandler();
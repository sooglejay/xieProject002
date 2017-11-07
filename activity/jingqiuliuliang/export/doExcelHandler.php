<?php
/**
 * Created by PhpStorm.
 * User: sooglejay
 * Date: 17/10/30
 * Time: 18:19
 */
require_once dirname(__FILE__) . "/JQLL_ExcelHandler.php";
require_once dirname(__FILE__) . "/../../../bootstrap.php";
require_once dirname(__FILE__) . "/../../../model/ExcelFlag.php";

class JQLL_Handler extends App
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
        $entities = $flagRepo->findBy(array("typeName" => "jqll_excel_export"));
        $exist = false;
        foreach ($entities as $entity) {
            if ($entity instanceof ExcelFlag) {
                $exist = true;
                break;
            }
        }
        if (!$exist) {
            $entity = new ExcelFlag();
            $entity->setTypeName("jqll_excel_export");
            $this->entityManager->persist($entity);
            $this->entityManager->flush($entity);
            // start to export
            $s = new JQLL_ExcelHandler();
            $s->doDownload();
        }
    }

    private
    function clear()
    {
        $flagRepo = $this->entityManager->getRepository("ExcelFlag");
        $entities = $flagRepo->findBy(array("typeName" => "jqll_excel_export"));
        foreach ($entities as $entity) {
            if ($entity instanceof ExcelFlag) {
                $this->entityManager->remove($entity);
                $this->entityManager->flush();
            }
        }
    }
}

new JQLL_Handler();
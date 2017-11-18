<?php
namespace End_2017;
/**
 * Created by PhpStorm.
 * User: sooglejay
 * Date: 17/10/30
 * Time: 18:19
 */
use App;
use ExcelFlag;

require_once dirname(__FILE__) . "/ExcelHandler.php";
require_once dirname(__FILE__) . "/../../../bootstrap.php";
require_once dirname(__FILE__) . "/../../../model/ExcelFlag.php";

class doExcelHandler extends App
{

    protected $flagName = 'end_2017';
    protected $columnName = 'typeName';
    protected $entityName = 'ExcelFlag';
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
        $flagRepo = $this->entityManager->getRepository($this->entityName);
        $entities = $flagRepo->findBy(array($this->columnName => $this->flagName));

        foreach ($entities as $entity) {
            if ($entity instanceof ExcelFlag) {
                return false;
            }
        }
        $entity = new ExcelFlag();
        $entity->setTypeName($this->flagName);
        $this->entityManager->persist($entity);
        $this->entityManager->flush($entity);
        // start to export
        $s = new ExcelHandler();
        $s->doDownload();
        return true;
    }

    private function clear()
    {
        $flagRepo = $this->entityManager->getRepository($this->entityName);
        $entities = $flagRepo->findBy(array($this->columnName => $this->flagName));
        foreach ($entities as $entity) {
            if ($entity instanceof ExcelFlag) {
                $this->entityManager->remove($entity);
                $this->entityManager->flush();
            }
        }
    }
}

new doExcelHandler();
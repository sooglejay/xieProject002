<?php
ini_set('date.timezone', 'Asia/Shanghai');


use Doctrine\ORM\EntityRepository;


/**
 * Created by PhpStorm.
 * User: sooglejay
 * Date: 17/8/23
 * Time: 22:34
 * @Entity @Table(name="excel_flag")
 */
class ExcelFlag
{
    /** @Id @Column(type="integer") @GeneratedValue * */
    protected $id;

    /** @Column(type="string")
     * 'shop'  'storeAndGive'
     *  * */
    protected $typeName;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getTypeName()
    {
        return $this->typeName;
    }

    /**
     * @param mixed $typeName
     */
    public function setTypeName($typeName)
    {
        $this->typeName = $typeName;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

}

class ExcelFlagRepository extends EntityRepository
{


}
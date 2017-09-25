<?php
ini_set('date.timezone', 'Asia/Shanghai');


use Doctrine\ORM\EntityRepository;


/**
 * Created by PhpStorm.
 * User: sooglejay
 * Date: 17/8/23
 * Time: 22:34
 * @Entity @Table(name="qian_dao")
 */
class QianDao
{

    /** @Id @Column(type="integer") @GeneratedValue * */
    protected $id;

    /** @Column(type="string")  * */
    protected $mobile_phone;

    /** @Column(type="string")  * */
    protected $openId;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getMobilePhone()
    {
        return $this->mobile_phone;
    }

    /**
     * @param mixed $mobile_phone
     */
    public function setMobilePhone($mobile_phone)
    {
        $this->mobile_phone = $mobile_phone;
    }

    /**
     * @return mixed
     */
    public function getOpenId()
    {
        return $this->openId;
    }

    /**
     * @param mixed $openId
     */
    public function setOpenId($openId)
    {
        $this->openId = $openId;
    }

}

class QianDaoRepository extends EntityRepository
{
    public function saveQianDao($obj)
    {
        $newObj = new QianDao();
        $newObj->setOpenId($obj["openId"]);
        $newObj->setMobilePhone($obj["mobilePhone"]);
        $this->_em->persist($newObj);
        $this->_em->flush();
    }

    public function fetchQianDao($openId)
    {
        return $this->findOneBy(array("openId" => $openId));
    }

}
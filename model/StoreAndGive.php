<?php
ini_set('date.timezone', 'Asia/Shanghai');

use Doctrine\ORM\EntityRepository;

/**
 * Created by PhpStorm.
 * User: sooglejay
 * Date: 17/8/23
 * Time: 22:34
 *
 * @Entity(repositoryClass="StoreAndGiveRepository") @Table(name="store_and_give")
 */
class StoreAndGive
{

    /** @Id  @GeneratedValue @Column(type="integer") */
    protected $id;

    /** @Column(type="string") */
    protected $phoneNumber;

    /** @Column(type="string") */
    protected $time;

    /**
     * @return mixed
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @param mixed $time
     */
    public function setTime($time)
    {
        $this->time = $time;
    }

    /**
     * StoreAndGive constructor.
     */
    public function __construct()
    {
        $this->time = date("Y-m-d H:i:s");
    }

    /**
     * @return mixed
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * @param mixed $phoneNumber
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;
    }

    /** @Column(type="string") */
    protected $userName;

    /** @Column(type="string") */
    protected $address;

    /** @Column(type="string") */
    protected $idCard;

    /** @Column(type="string") */
    protected $area;

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
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * @param mixed $userName
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return mixed
     */
    public function getIdCard()
    {
        return $this->idCard;
    }

    /**
     * @param mixed $idCard
     */
    public function setIdCard($idCard)
    {
        $this->idCard = $idCard;
    }

    /**
     * @return mixed
     */
    public function getArea()
    {
        return $this->area;
    }

    /**
     * @param mixed $area
     */
    public function setArea($area)
    {
        $this->area = $area;
    }


    public function toArray()
    {
        return array("phoneNumber" => $this->getPhoneNumber(),
            "address" => $this->getAddress(),
            "idCard" => $this->getIdCard(),
            "userName" => $this->getUserName(),
            "area" => $this->getArea()
        );
    }
}

class StoreAndGiveRepository extends EntityRepository
{
    public function saveEntity($obj)
    {
        $entity = new StoreAndGive();
        $entity->setArea($obj['area']);
        $entity->setUserName($obj['userName']);
        $entity->setAddress($obj['address']);
        $entity->setPhoneNumber($obj['phoneNumber']);
        $entity->setIdCard($obj['idCard']);
        $this->_em->persist($entity);
        $this->_em->flush();
    }
}
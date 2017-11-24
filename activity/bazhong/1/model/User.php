<?php
namespace BaZhong;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;

require_once dirname(__FILE__) . '/Sign.php';

/**
 * Created by PhpStorm.
 * User: sooglejay
 * Date: 17/11/16
 * Time: 13:06
 * @Entity(repositoryClass="BZUserRepository")
 * @Table(name="bz_user")
 */
class User
{
    /** @Id @Column(type="integer") @GeneratedValue * */
    protected $id;

    /** @Column(type="string") * */
    protected $phoneNumber;

    /** @Column(type="string") * */
    protected $openId;

    /** @OneToMany(targetEntity="Sign",mappedBy="user") * */
    protected $signList;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->signList = new ArrayCollection();
    }

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

    /**
     * @return mixed
     */
    public function getSignList()
    {
        return $this->signList;
    }

    /**
     * @param Sign $sign
     */
    public function addSignList($sign)
    {
        $this->signList[] = $sign;
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

class BZUserRepository extends EntityRepository
{

}
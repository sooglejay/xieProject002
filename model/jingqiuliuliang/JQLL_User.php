<?php
ini_set('date.timezone', 'Asia/Shanghai');

use Doctrine\ORM\EntityRepository;

/**
 * Created by PhpStorm.
 * User: sooglejay
 * Date: 17/8/23
 * Time: 22:34
 * 购买了 88、138、158、238 套餐的用户结构
 *
 * @Entity(repositoryClass="JQLL_UserRepository") @Table(name="jqll_user")
 */
class JQLL_User
{

    /** @Id  @GeneratedValue @Column(type="integer") */
    protected $id;

    /** @Column(type="string") */
    protected $mobileNumber;

    /** @Column(type="string") */
    protected $zifei_code;

    /** @Column(type="string") */
    protected $zifei_name;
    /** @Column(type="string") */
    protected $address;

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

    /** @Column(type="integer")
     * 28 38 48 58 88 138
     */
    protected $type;

    /** @Column(type="integer")
     *
     */
    protected $isChosen;

    /** @Column(type="string") * */
    protected $time;

    /**
     * @return mixed
     */
    public function getIsChosen()
    {
        return $this->isChosen;
    }

    /**
     * @param mixed $isChosen
     */
    public function setIsChosen($isChosen)
    {
        $this->isChosen = $isChosen;
    }

    /**
     *
     */
    public function __construct()
    {
        $this->time = date("Y-m-d H:i:s");
    }

    /**
     * @return mixed
     */
    public function getTime()
    {
        return $this->time;
    }

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
    public function getMobileNumber()
    {
        return $this->mobileNumber;
    }

    /**
     * @param mixed $mobileNumber
     */
    public function setMobileNumber($mobileNumber)
    {
        $this->mobileNumber = $mobileNumber;
    }

    /**
     * @return mixed
     */
    public function getZifeiCode()
    {
        return $this->zifei_code;
    }

    /**
     * @param mixed $zifei_code
     */
    public function setZifeiCode($zifei_code)
    {
        $this->zifei_code = $zifei_code;
    }

    /**
     * @return mixed
     */
    public function getZifeiName()
    {
        return $this->zifei_name;
    }

    /**
     * @param mixed $zifei_name
     */
    public function setZifeiName($zifei_name)
    {
        $this->zifei_name = $zifei_name;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }
}

class JQLL_UserRepository extends EntityRepository
{
    public function deleteAll()
    {
        $e = $this->_em->getRepository("JQLL_User");
        $repo = $e->findAll();
        $i = 0;
        foreach ($repo as $r) {
            if ($r instanceof JQLL_User) {
                $this->_em->remove($r);
                $i++;
                if ($i % 20 == 0) {
                    $this->_em->flush();
                    $i = 0;
                }
            }
        }
        if ($i > 0) {
            $this->_em->flush();
        }
    }
}
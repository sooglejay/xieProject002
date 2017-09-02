<?php
use Doctrine\ORM\EntityRepository;

/**
 * Created by PhpStorm.
 * User: sooglejay
 * Date: 17/8/23
 * Time: 22:34
 *
 * @Entity(repositoryClass="ActivitySepRepository") @Table(name="activity_sep_user")
 */
class ActivitySepUser
{

    /** @Id  @GeneratedValue @Column(type="integer") */
    protected $id;

    /** @Column(type="string") */
    protected $mobileNumber;

    /** @Column(type="integer") */
    protected $type_88;

    /** @Column(type="integer") */
    protected $type_138;

    /** @Column(type="integer") */
    protected $type_158;

    /** @Column(type="integer") */
    protected $type_238;

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
    public function getType88()
    {
        return $this->type_88;
    }

    /**
     * @param mixed $type_88
     */
    public function setType88($type_88)
    {
        $this->type_88 = $type_88;
    }

    /**
     * @return mixed
     */
    public function getType138()
    {
        return $this->type_138;
    }

    /**
     * @param mixed $type_138
     */
    public function setType138($type_138)
    {
        $this->type_138 = $type_138;
    }

    /**
     * @return mixed
     */
    public function getType158()
    {
        return $this->type_158;
    }

    /**
     * @param mixed $type_158
     */
    public function setType158($type_158)
    {
        $this->type_158 = $type_158;
    }

    /**
     * @return mixed
     */
    public function getType238()
    {
        return $this->type_238;
    }

    /**
     * @param mixed $type_238
     */
    public function setType238($type_238)
    {
        $this->type_238 = $type_238;
    }

    public function toArray()
    {
        return array("mobile" => $this->getMobileNumber(),
            "type_88" => $this->getType88(),
            "type_138" => $this->getType138(),
            "type_158" => $this->getType158(),
            "type_238" => $this->getType238()
        );
    }
}

class ActivitySepRepository extends EntityRepository
{

}
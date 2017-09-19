<?php
ini_set('date.timezone', 'Asia/Shanghai');

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;


/**
 * Created by PhpStorm.
 * User: sooglejay
 * Date: 17/8/23
 * Time: 22:34
 * @Entity(repositoryClass="TouSuRepository")
 * @Table(name="tousu")
 */
class TouSu
{
    /** @Id @Column(type="integer") @GeneratedValue * */
    protected $id;

    /** @Column(type="string") * */
    protected $khxm;

    /** @Column(type="string") * */
    protected $khdh;

    /** @Column(type="string") * */
    protected $lxdh;

    /** @Column(type="string") * */
    protected $tsnr;

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
    public function getKhxm()
    {
        return $this->khxm;
    }

    /**
     * @param mixed $khxm
     */
    public function setKhxm($khxm)
    {
        $this->khxm = $khxm;
    }

    /**
     * @return mixed
     */
    public function getKhdh()
    {
        return $this->khdh;
    }

    /**
     * @param mixed $khdh
     */
    public function setKhdh($khdh)
    {
        $this->khdh = $khdh;
    }

    /**
     * @return mixed
     */
    public function getLxdh()
    {
        return $this->lxdh;
    }

    /**
     * @param mixed $lxdh
     */
    public function setLxdh($lxdh)
    {
        $this->lxdh = $lxdh;
    }

    /**
     * @return mixed
     */
    public function getTsnr()
    {
        return $this->tsnr;
    }

    /**
     * @param mixed $tsnr
     */
    public function setTsnr($tsnr)
    {
        $this->tsnr = $tsnr;
    }

}

class TouSuRepository extends EntityRepository
{
    public function saveTouSu($obj)
    {
        $newObj = new TouSu();
        $newObj->setKhdh($obj["khdh"]);
        $newObj->setKhxm($obj["khxm"]);
        $newObj->setLxdh($obj["lxdh"]);
        $newObj->setTsnr($obj["tsnr"]);
        $this->_em->persist($newObj);
        $this->_em->flush();
    }
}
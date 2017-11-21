<?php
namespace BaZhong;

use Doctrine\ORM\EntityRepository;

/**
 * Created by PhpStorm.
 * User: sooglejay
 * Date: 17/11/16
 * Time: 13:06
 * @Entity(repositoryClass="BZAllPhoneSegmentsRepository")
 * @Table(name="bz_all_phone_segments")
 */
class AllPhoneSegments
{
    /** @Id @Column(type="integer") @GeneratedValue * */
    protected $id;


    /**
     * @Column(type="string")
     * @var
     */
    protected $phoneNumberSeg;

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
    public function getPhoneNumberSeg()
    {
        return $this->phoneNumberSeg;
    }

    /**
     * @param mixed $phoneNumberSeg
     */
    public function setPhoneNumberSeg($phoneNumberSeg)
    {
        $this->phoneNumberSeg = $phoneNumberSeg;
    }


}

class BZAllPhoneSegmentsRepository extends EntityRepository
{
    /**
     * 添加用户
     * @param string $phoneNumberSeg 手机号码段
     * @return array
     */
    public function savePhoneSegment($phoneNumberSeg)
    {
        $e = $this->findOneBy(array("phoneNumberSeg" => $phoneNumberSeg));
        if (is_null($e)) {
            $e = new AllPhoneSegments();
            $e->setPhoneNumberSeg($phoneNumberSeg);
            $this->_em->persist($e);
            $this->_em->flush($e);
            return array("message" => "添加号码段成功！", "code" => 200);
        }
        return array("message" => "您已经添加过该号码段", "code" => 201);
    }

    public function checkExist($phoneSeg)
    {
        $e = $this->findOneBy(array("phoneNumberSeg" => $phoneSeg));
        if ($e instanceof AllPhoneSegments) {
            return true;
        }
        return false;
    }
}
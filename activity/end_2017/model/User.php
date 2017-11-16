<?php
namespace End_2017;

use Doctrine\ORM\EntityRepository;

/**
 * Created by PhpStorm.
 * User: sooglejay
 * Date: 17/11/16
 * Time: 13:06
 * @Entity(repositoryClass="End2017UserRepository")
 * @Table(name="end_2017_user")
 */
class User
{
    /** @Id @Column(type="integer") @GeneratedValue * */
    protected $id;

    /**
     * Many phoneNumber have One Type. 谁写inversdBy，谁就是拥有这个关系的owner
     * @ManyToOne(targetEntity="UserType", inversedBy="phoneNumbers")
     * @JoinColumn(name="user_type_id", referencedColumnName="id")
     */
    protected $userType;

    /**
     * One User has one Order
     * @OneToOne(targetEntity="Order", mappedBy="user")
     */
    protected $order;

    /**
     * @return Order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @Column(type="string")
     * @var
     */
    protected $phoneNumber;

    /**
     * @return UserType
     */
    public function getUserType()
    {
        return $this->userType;
    }

    /**
     * @param UserType $user_type
     */
    public function setUserType($user_type)
    {
        $this->userType = $user_type;
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
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param Order $order
     */
    public function setOrder($order)
    {
        $this->order = $order;
    }

}

class End2017UserRepository extends EntityRepository
{
    /**
     * 添加用户
     * @param string $phoneNumber 用户的手机号码
     * @param integer $typeVal  属于第几类用户
     * @return array
     */
    protected function saveUser($phoneNumber, $typeVal)
    {
        $userTypeRepo = $this->_em->getRepository("UserType");
        $userRepo = $this->_em->getRepository("User");
        $e = $userRepo->findOneBy(array("phoneNumber" => $phoneNumber));
        if (!is_null($e)) {
            return array("message" => "您已经添加过此用户", "code" => 201);
        }
        $userTypeEntity = $userTypeRepo->findOneBy(array("typeVal"=>$typeVal));
        if ($userTypeEntity instanceof UserType) {
            $userEntity = new User();
            $userEntity->setPhoneNumber($phoneNumber);
            $userEntity->setUserType($userTypeEntity);
            $userTypeEntity->addUser($userEntity);
            $this->_em->persist($userEntity);
            $this->_em->persist($userTypeEntity);
            $this->_em->flush();
            return array("message" => "添加成功！", "code" => 200);
        }
        return array("message" => "找不到实例，请联系管理员！", "code" => 201);
    }
}
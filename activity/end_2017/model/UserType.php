<?php
namespace End_2017;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;

/**
 * Created by PhpStorm.
 * User: sooglejay
 * Date: 17/11/16
 * Time: 13:06
 * @Entity(repositoryClass="End2017UserTypeRepository")
 * @Table(name="end_2017_user_type")
 */
class UserType
{
    /** @Id @Column(type="integer") @GeneratedValue * */
    protected $id;

    /**
     * One UserType has Many Users.
     * @OneToMany(targetEntity="User", mappedBy="type")
     */
    protected $users;

    /**
     * 类型的值
     * @Column(type="integer")
     * @var
     */
    protected $typeVal;

    /**
     * 类型的描述
     * @Column(type="string")
     * @var
     */
    protected $typeDes;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param User $user
     */
    public function addUser($user)
    {
        $this->users[] = $user;
    }

    /**
     * @return mixed
     */
    public function getTypeName()
    {
        return $this->typeDes;
    }

    /**
     * @param string $typeDes
     */
    public function setTypeDes($typeDes)
    {
        $this->typeDes = $typeDes;
    }

    /**
     * @return mixed
     */
    public function getTypeVal()
    {
        return $this->typeVal;
    }

    /**
     * @param mixed $typeVal
     */
    public function setTypeVal($typeVal)
    {
        $this->typeVal = $typeVal;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

}

class End2017UserTypeRepository extends EntityRepository
{
    /**
     * @param $typeDes
     * @param $typeVal
     * @return array
     */
    protected function addUserType($typeDes, $typeVal)
    {
        $e = $this->findOneBy(array("typeVal" => $typeVal));
        if (is_null($e)) {
            $e = new UserType();
            $e->setTypeDes($typeDes);
            $e->setTypeVal($typeVal);
            $this->_em->persist($e);
            $this->_em->flush($e);
            return array("message" => "添加用户类型成功！", "code" => 200);
        }
        return array("message" => "您已经添加过该用户类型！", "code" => 201);
    }
}
<?php
namespace End_2017;

use Doctrine\ORM\EntityRepository;

/**
 * Created by PhpStorm.
 * User: sooglejay
 * Date: 17/11/16
 * Time: 13:06
 * @Entity(repositoryClass="End2017ActivityTypeRepository")
 * @Table(name="end_2017_activity_type")
 */
class ActivityType
{
    /** @Id @Column(type="integer") @GeneratedValue * */
    protected $id;

    /**
     * @ManyToOne(targetEntity="UserType",inversedBy="activityTypes")
     * @JoinColumn(name="user_type_id", referencedColumnName="id")
     */
    protected $userType;

    /**
     * @return UserType
     */
    public function getUserType()
    {
        return $this->userType;
    }

    /**
     * @param UserType $userType
     */
    public function setUserType($userType)
    {
        $this->userType = $userType;
    }

    /**
     * 活动的名字
     * @Column(type="string")
     * @var
     */
    protected $activityName;

    /**
     * 短信办理方式（发送以下代码到10086）
     * @Column(type="string")
     * @var
     */
    protected $activityCode;

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
    public function getActivityName()
    {
        return $this->activityName;
    }

    /**
     * @param mixed $activityName
     */
    public function setActivityName($activityName)
    {
        $this->activityName = $activityName;
    }

    /**
     * @return mixed
     */
    public function getActivityCode()
    {
        return $this->activityCode;
    }

    /**
     * @param mixed $activityCode
     */
    public function setActivityCode($activityCode)
    {
        $this->activityCode = $activityCode;
    }


}

class End2017ActivityTypeRepository extends EntityRepository
{
    public function saveActivityType($userTypeVal, $activityCode, $activityName)
    {
        $e = $this->findOneBy(array("activityCode" => $activityCode));
        if (is_null($e)) {
            $userTypeRepo = $this->_em->getRepository('End_2017\UserType');
            if ($userTypeRepo instanceof End2017UserTypeRepository) {
                $userTypeEntity = $userTypeRepo->findOneBy(array("typeVal" => $userTypeVal));
                if ($userTypeEntity instanceof UserType) {
                    $e = new ActivityType();
                    $e->setActivityCode($activityCode);
                    $e->setActivityName($activityName);
                    $e->setUserType($userTypeEntity);
                    $userTypeEntity->addActivityTypes($e);
                    $this->_em->persist($e);
                    $this->_em->persist($userTypeEntity);
                    $this->_em->flush();
                    return array("message" => "添加活动类型成功！", "code" => 200);
                } else {
                    return array("message" => "目标用户类型不存在！", "code" => 201);
                }
            }
        }
        return array("message" => "您已经添加过该活动类型！", "code" => 201);
    }

    public function getEntityByActivityCode($activityCode)
    {
        return $this->findOneBy(array("activityCode" => $activityCode));
    }
}
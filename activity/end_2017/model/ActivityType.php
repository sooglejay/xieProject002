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
    protected function saveActivityType($activityCode, $activityName)
    {
        $e = $this->findOneBy(array("activityCode" => $activityCode));
        if (is_null($e)) {
            $e = new ActivityType();
            $e->setActivityCode($activityCode);
            $e->setActivityName($activityName);
            $this->_em->persist($e);
            $this->_em->flush();
            return array("message" => "添加活动类型成功！", "code" => 200);
        }
        return array("message" => "您已经添加过该活动类型！", "code" => 201);
    }

}
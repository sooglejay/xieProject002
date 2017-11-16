<?php
namespace End_2017;
require_once dirname(__FILE__) . "/../model/User.php";
require_once dirname(__FILE__) . "/../model/ActivityType.php";

use Doctrine\ORM\EntityRepository;

/**
 * Created by PhpStorm.
 * User: sooglejay
 * Date: 17/11/16
 * Time: 13:06
 * @Entity(repositoryClass="End2017OrderRepository")
 * @Table(name="end_2017_order")
 */
class Order
{
    /** @Id @Column(type="integer") @GeneratedValue * */
    protected $id;

    /**
     * @OneToOne(targetEntity="User", inversedBy="order")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @ManyToOne(targetEntity="ActivityType",inversedBy="order")
     * @JoinColumn(name="activity_type_id", referencedColumnName="id")
     */
    protected $activityType;

    /**  @Column(type="string") @GeneratedValue * */
    protected $time;

    /**
     * Order constructor.
     */
    public function __construct()
    {
        $this->time = date("Y-m-d H:i:s");
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return ActivityType
     */
    public function getActivityType()
    {
        return $this->activityType;
    }

    /**
     * @param mixed $activityType
     */
    public function setActivityType($activityType)
    {
        $this->activityType = $activityType;
    }

    /**
     * @return mixed
     */
    public function getTime()
    {
        return $this->time;
    }

}

class End2017OrderRepository extends EntityRepository
{
    /**
     * @param User $userEntity
     * @param ActivityType $activityTypeEntity
     * @return array
     */
    protected function saveOrder($userEntity, $activityTypeEntity)
    {
        if ($userEntity instanceof User && $activityTypeEntity instanceof ActivityType) {
            if (!is_null($userEntity->getOrder())) {
                return array("message" => "您已经参与过活动，请勿重复参与！", "code" => 201);
            }
            $order = new Order();
            $order->setUser($userEntity);
            $userEntity->setOrder($order);
            $order->setActivityType($activityTypeEntity);
            $this->_em->persist($userEntity);
            $this->_em->persist($order);
            $this->_em->flush();
            return array("message" => "办理成功！", "code" => 200);
        }
        return array("message" => "找不到实例，请联系管理员！", "code" => 201);
    }
}
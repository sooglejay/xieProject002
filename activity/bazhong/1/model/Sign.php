<?php
namespace BaZhong;

use Doctrine\ORM\EntityRepository;

require_once dirname(__FILE__) . '/User.php';

/**
 * Created by PhpStorm.
 * User: sooglejay
 * Date: 17/11/16
 * Time: 13:06
 * @Entity(repositoryClass="BZSignRepository")
 * @Table(name="bz_sign")
 */
class Sign
{
    /** @Id @Column(type="integer") @GeneratedValue * */
    protected $id;

    /**
     * @ManyToOne(targetEntity="User", inversedBy="signList")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;
    /**  @Column(type="datetime", nullable=false) * */
    protected $time;

    /**
     * Order constructor.
     */
    public function __construct()
    {
        $this->time = new \DateTime("now");
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
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return \DateTime
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @param mixed $time
     */
    public function setTime($time)
    {
        $this->time = $time;
    }

}

class BZSignRepository extends EntityRepository
{

}
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


    /**  @Column(type="string") @GeneratedValue * */
    protected $time;

    /**
     * Order constructor.
     */
    public function __construct()
    {
        $this->time = date("Y-m-d H:i:s");
    }


}

class BZSignRepository extends EntityRepository
{

}
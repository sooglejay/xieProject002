<?php
ini_set('date.timezone', 'Asia/Shanghai');

use Doctrine\ORM\EntityRepository;

require_once 'User.php';

/**
 * Created by PhpStorm.
 * User: sooglejay
 * Date: 17/8/23
 * Time: 22:34
 * @Entity(repositoryClass="ShopRepository")
 * @Table(name="shop")
 */
class Shop
{
    /** @Id @GeneratedValue @Column(type="integer") */
    protected $id;

    /** @Column(type="string") * */
    protected $shop_name;

    /** @Column(type="string") * */
    protected $shop_addr;

    /** @Column(type="string") * */
    protected $shop_unique_code;

    /**
     * @return mixed
     */
    public function getShopUniqueCode()
    {
        return $this->shop_unique_code;
    }

    /**
     * @param mixed $shop_unique_code
     */
    public function setShopUniqueCode($shop_unique_code)
    {
        $this->shop_unique_code = $shop_unique_code;
    }


    /** @Column(type="string") * */
    protected $shop_street;

    /** @Column(type="string") * */
    protected $shop_contact1;

    /** @Column(type="string") * */
    protected $shop_contact2;

    /** @Column(type="string") * */
    protected $shop_type;

    /** @Column(type="string") * */
    protected $shop_280;

    /** @Column(type="string") * */
    protected $shop_group_net;

    /** @Column(type="bigint") * */
    protected $shop_mem_num;

    /** @Column(type="string") * */
    protected $shop_209;


    /** @Column(type="string") * */
    protected $shop_broadband_cover;

    /** @Column(type="string") * */
    protected $shop_landline;

    /** @Column(type="string") * */
    protected $shop_operator;

    /** @Column(type="string") * */
    protected $shop_lng;

    /** @Column(type="string") * */
    protected $shop_lat;

    /** @Column(type="string") * */
    protected $time;

    /**
     * @return mixed
     */
    public function getShopLng()
    {
        return $this->shop_lng;
    }

    /**
     * @param mixed $shop_lng
     */
    public function setShopLng($shop_lng)
    {
        $this->shop_lng = $shop_lng;
    }

    /**
     * @return mixed
     */
    public function getShopLat()
    {
        return $this->shop_lat;
    }

    /**
     * @param mixed $shop_lat
     */
    public function setShopLat($shop_lat)
    {
        $this->shop_lat = $shop_lat;
    }

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
    public function getShopName()
    {
        return $this->shop_name;
    }

    /**
     * @param mixed $shop_name
     */
    public function setShopName($shop_name)
    {
        $this->shop_name = $shop_name;
    }

    /**
     * @return mixed
     */
    public function getShopAddr()
    {
        return $this->shop_addr;
    }

    /**
     * @param mixed $shop_addr
     */
    public function setShopAddr($shop_addr)
    {
        $this->shop_addr = $shop_addr;
    }

    /**
     * @return mixed
     */
    public function getShopStreet()
    {
        return $this->shop_street;
    }

    /**
     * @param mixed $shop_street
     */
    public function setShopStreet($shop_street)
    {
        $this->shop_street = $shop_street;
    }

    /**
     * @return mixed
     */
    public function getShopContact1()
    {
        return $this->shop_contact1;
    }

    /**
     * @param mixed $shop_contact1
     */
    public function setShopContact1($shop_contact1)
    {
        $this->shop_contact1 = $shop_contact1;
    }

    /**
     * @return mixed
     */
    public function getShopContact2()
    {
        return $this->shop_contact2;
    }

    /**
     * @param mixed $shop_contact2
     */
    public function setShopContact2($shop_contact2)
    {
        $this->shop_contact2 = $shop_contact2;
    }

    /**
     * @return mixed
     */
    public function getShopType()
    {
        return $this->shop_type;
    }

    /**
     * @param mixed $shop_type
     */
    public function setShopType($shop_type)
    {
        $this->shop_type = $shop_type;
    }

    /**
     * @return mixed
     */
    public function getShop280()
    {
        return $this->shop_280;
    }

    /**
     * @param mixed $shop_280
     */
    public function setShop280($shop_280)
    {
        $this->shop_280 = $shop_280;
    }

    /**
     * @return mixed
     */
    public function getShopGroupNet()
    {
        return $this->shop_group_net;
    }

    /**
     * @param mixed $shop_group_net
     */
    public function setShopGroupNet($shop_group_net)
    {
        $this->shop_group_net = $shop_group_net;
    }

    /**
     * @return mixed
     */
    public function getShopMemNum()
    {
        return $this->shop_mem_num;
    }

    /**
     * @param mixed $shop_mem_num
     */
    public function setShopMemNum($shop_mem_num)
    {
        $this->shop_mem_num = $shop_mem_num;
    }

    /**
     * @ManyToOne(targetEntity="User", inversedBy="assignedShop")
     **/
    protected $shopUser;

    public function setShopUser(User $user)
    {
        $user->assignedToShop($this);
        $this->shopUser = $user;
    }

    /**
     * @return mixed
     */
    public function getShopUser()
    {
        return $this->shopUser;
    }

    /**
     * @return mixed
     */
    public function getShop209()
    {
        return $this->shop_209;
    }

    /**
     * @param mixed $shop_209
     */
    public function setShop209($shop_209)
    {
        $this->shop_209 = $shop_209;
    }

    /**
     * @return mixed
     */
    public function getShopBroadbandCover()
    {
        return $this->shop_broadband_cover;
    }

    /**
     * @param mixed $shop_broadband_cover
     */
    public function setShopBroadbandCover($shop_broadband_cover)
    {
        $this->shop_broadband_cover = $shop_broadband_cover;
    }

    /**
     * @return mixed
     */
    public function getShopLandline()
    {
        return $this->shop_landline;
    }

    /**
     * @param mixed $shop_landline
     */
    public function setShopLandline($shop_landline)
    {
        $this->shop_landline = $shop_landline;
    }

    /**
     * @return mixed
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @param $t
     */
    public function setTime($t=null)
    {
        if(isset($t)){
            $this->time = $t;
        }else{
            $this->time = date("Y-m-d H:i:s");
        }
    }

    /**
     * @return mixed
     */
    public function getShopOperator()
    {
        return $this->shop_operator;
    }

    /**
     * @param mixed $shop_operator
     */
    public function setShopOperator($shop_operator)
    {
        $this->shop_operator = $shop_operator;
    }

    public function toArray()
    {
        return array(
            "id" => $this->getId(),
            "shop_name" => $this->shop_name,
            "shop_addr" => $this->shop_addr,
            "shop_contact1" => $this->shop_contact1,
            "shop_contact2" => $this->shop_contact2,
            "shop_broadband_cover" => $this->shop_broadband_cover,
            "shop_group_net" => $this->shop_group_net,
            "shop_landline" => $this->shop_landline,
            "shop_280" => $this->shop_280,
            "shop_type" => $this->shop_type,
            "shop_209" => $this->shop_209,
            "shop_mem_num" => $this->shop_mem_num,
            "shop_street" => $this->shop_street,
            "shop_operator" => $this->shop_operator,
            "shop_lng" => $this->shop_lng,
            "shop_lat" => $this->shop_lat
        );
    }

}

class ShopRepository extends EntityRepository
{
}
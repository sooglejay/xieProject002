<?php
use Doctrine\Common\Collections\ArrayCollection;

require_once 'Shop.php';

/**
 * Created by PhpStorm.
 * User: sooglejay
 * Date: 17/8/23
 * Time: 22:34
 * @Entity @Table(name="user")
 */
class User
{

    /** @Id @Column(type="integer") @GeneratedValue * */
    protected $id;

    /** @Column(type="string") * */
    protected $city;

    /** @Column(type="string") * */
    protected $county;

    /** @Column(type="string") * */
    protected $code;

    /** @Column(type="string") * */
    protected $selling_area_name;

    /** @Column(type="string") * */
    protected $area_name;

    /** @Column(type="string") * */
    protected $grid_name;

    /** @Column(type="string") * */
    protected $account_name;

    /** @Column(type="integer") * */
    protected $shop_num;
    /**
     * @OneToMany(targetEntity="Shop", mappedBy="shopUser")
     * @var Shop[] An ArrayCollection of Shop objects.
     **/
    protected $assignedShop;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->assignedShop = new ArrayCollection();
    }

    public function assignedToShop(Shop $shop)
    {
        $this->assignedShop[] = $shop;
        $this->shop_num++;
    }

    /**
     * @return Shop[]
     */
    public function getAssignedShop()
    {
        return $this->assignedShop;
    }

    /**
     * @return mixed
     */
    public function getShopNum()
    {
        return $this->shop_num;
    }

    /**
     * @param mixed $shop_num
     */
    public function setShopNum($shop_num)
    {
        $this->shop_num = $shop_num;
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
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return mixed
     */
    public function getCounty()
    {
        return $this->county;
    }

    /**
     * @param mixed $county
     */
    public function setCounty($county)
    {
        $this->county = $county;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return mixed
     */
    public function getSellingAreaName()
    {
        return $this->selling_area_name;
    }

    /**
     * @param mixed $selling_area_name
     */
    public function setSellingAreaName($selling_area_name)
    {
        $this->selling_area_name = $selling_area_name;
    }

    /**
     * @return mixed
     */
    public function getAreaName()
    {
        return $this->area_name;
    }

    /**
     * @param mixed $area_name
     */
    public function setAreaName($area_name)
    {
        $this->area_name = $area_name;
    }

    /**
     * @return mixed
     */
    public function getGridName()
    {
        return $this->grid_name;
    }

    /**
     * @param mixed $grid_name
     */
    public function setGridName($grid_name)
    {
        $this->grid_name = $grid_name;
    }

    /**
     * @return mixed
     */
    public function getAccountName()
    {
        return $this->account_name;
    }

    /**
     * @param mixed $account_name
     */
    public function setAccountName($account_name)
    {
        $this->account_name = $account_name;
    }

    public function toArray()
    {
        return array("city" => $this->city,
            "county" => $this->county,
            "code" => $this->code,
            "selling_area_name" => $this->selling_area_name,
            "area_name" => $this->area_name,
            "grid_name" => $this->grid_name,
            "account_name" => $this->account_name,
            "shop_num" => $this->shop_num,
        );
    }

}
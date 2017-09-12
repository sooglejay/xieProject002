<?php
use Doctrine\ORM\EntityRepository;

/**
 *
 * @Entity(repositoryClass="PeopleRepository")
 *
 * // 表明
 * @Table(name="people")
 */
class People
{
    /** @Id @Column(type="integer") @GeneratedValue * */
    protected $id;
    /** @Column(type="string") * */
    public $name;
    /** @Column(type="integer") * */
    protected $age;

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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * @param mixed $age
     */
    public function setAge($age)
    {
        $this->age = $age;
    }


}

class PeopleRepository extends EntityRepository
{
    public function createPeople($userName, $age)
    {
        $people = new People();
        $people->setName($userName);
        $people->setAge($age);

        // 把对象持久化,写入缓存，还没有真正写入数据库
        $this->_em->persist($people);
        $this->_em->flush($people);
    }
}


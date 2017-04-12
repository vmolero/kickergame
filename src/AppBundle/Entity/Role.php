<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Interfaces\RoleHolder;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="role")
 */
class Role implements RoleHolder
{
    const ADMIN = 'ROLE_ADMIN';
    const PLAYER = 'ROLE_PLAYER';

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\Column(type="string", unique=true)
     */
    protected $type;
    /**
     * @ORM\Column(type="string")
     */
    protected $description;

    public static function create($string)
    {
        $c = new Role();
        switch ($string)
        {
            case self::ADMIN:
            case 'admin':
                $c->setType(self::ADMIN);
                break;
            case self::PLAYER:
            case 'player':
                $c->setType(self::PLAYER);
                break;
        }

        return $c;
    }

    public static function getValidRoles() {
        return [self::ADMIN, self::PLAYER];
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
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }


}
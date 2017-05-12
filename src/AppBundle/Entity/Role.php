<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Interfaces\RoleHolder;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Role\RoleInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="role")
 */
class Role implements RoleInterface
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
    protected $role;
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
                $c->setRole(self::ADMIN);
                break;
            case self::PLAYER:
            case 'player':
                $c->setRole(self::PLAYER);
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
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param mixed $role
     */
    public function setRole($role)
    {
        $this->role = $role;
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
<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Interfaces\RoleHolder;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Entity\User as BaseUser;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @ORM\Table(name="user")
 */
class User extends BaseUser
{
    const REPOSITORY = 'AppBundle:User';
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     */
    protected $id;

    /**
     * @ORM\Column(name="full_name", type="string", length=255)
     *
     * @Assert\NotBlank(message="Please enter your name.", groups={"Registration"})
     * @Assert\Length(
     *     min=3,
     *     max=255,
     *     minMessage="The name is too short.",
     *     maxMessage="The name is too long.",
     *     groups={"Registration"}
     * )
     */
    protected $fullName;

    public static function createPlayer()
    {
        $player = new User();
        $player->addRole(Role::create(Role::PLAYER));

        return $player;
    }

    public function addRole($role)
    {
        is_string($role) && ($role = Role::create($role));

        return $this->addToRoleArray($role);
    }

    private function addToRoleArray(RoleHolder $role)
    {
        $roleCode = strtoupper($role->getType());
        if (!in_array($roleCode, $this->roles, true) &&
            $this->isValidRole($roleCode)
        ) {
            $this->roles[] = $roleCode;
        }

        return $this;
    }

    private function isValidRole($roleCode)
    {
        return is_string($roleCode) &&
            in_array($roleCode, Role::getValidRoles()) &&
            preg_match('/^ROLE\_/', $roleCode) === 1;
    }

    public static function createAdmin()
    {
        $user = new User();
        $user->addRole(Role::create(Role::ADMIN));

        return $user;
    }

    /**
     * @return mixed
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * @param mixed $fullName
     * @return User
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function hasRole($role)
    {
        return in_array($role, $this->getRoles());
    }

    /**
     * Returns the user roles
     *
     * @return array The roles
     */
    public function getRoles()
    {
        $roles = $this->roles;
        foreach ($this->getGroups() as $group) {
            $roles = array_merge($roles, $group->getRoles());
        }

        return array_unique($roles);
    }
}
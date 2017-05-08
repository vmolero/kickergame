<?php

namespace AppBundle\Domain;

use AppBundle\Entity\Role;
use Symfony\Component\Security\Core\Role\RoleInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class Player implements RoleInterface, UserInterface
{
    private $user;

    /**
     * Player constructor.
     */
    public function __construct(UserInterface $user)
    {
        $this->user = $user;
    }

    public function getRole()
    {
        return Role::PLAYER;
    }

    public function getRoles()
    {
        return $this->user->getRoles();
    }

    public function getPassword()
    {
        return $this->user->getPassword();
    }

    public function getSalt()
    {
        return $this->user->getSalt();
    }

    public function getUsername()
    {
        return $this->user->getUsername();
    }

    public function eraseCredentials()
    {
        return $this->user->eraseCredentials();
    }


}
<?php
namespace AppBundle\Domain\Users;

use AppBundle\Domain\Role;
use AppBundle\Entity\User;

class Player extends User
{
    public function __construc()
    {
        parent::addRole(Role::create(Role::PLAYER));
    }

    public function addRole($role)
    {

    }
}
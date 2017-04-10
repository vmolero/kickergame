<?php
namespace AppBundle\Domain\Users;

use AppBundle\Domain\Role;
use AppBundle\Domain\User;

class Player extends User
{
    public function __construct()
    {
        parent::addRole(Role::create(Role::PLAYER));
    }

    public function addRole($role)
    {

    }
}
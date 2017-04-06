<?php
namespace AppBundle\Domain\Users;

use AppBundle\Domain\Role;
use AppBundle\Entity\User;

/**
 * Created by PhpStorm.
 * User: vmolero
 * Date: 4/6/17
 * Time: 10:07 AM
 */
class Admin extends User
{
    public function __construc()
    {
        parent::addRole(Role::create(Role::ADMIN));
    }

    public function addRole($role)
    {

    }
}
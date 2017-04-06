<?php

namespace AppBundle\Test\Domain;

use AppBundle\Domain\Role;
use AppBundle\Entity\User;

class UserTest extends \PHPUnit_Framework_TestCase
{
    public function setUp() {

    }

    public function testAddRole()
    {
        $role1 = Role::create('admin');
        $role2 = Role::create('player')->getCode();
        $user = new User();
        $user->addRole($role1);
        $user->addRole($role2);
        $roles = $user->getRoles();
        $this->assertEquals(['ROLE_ADMIN', 'ROLE_USER'], $roles);
    }
}

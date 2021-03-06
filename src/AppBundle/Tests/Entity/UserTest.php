<?php

namespace AppBundle\Tests\Entity;

use AppBundle\Entity\Role;
use AppBundle\Entity\User;

class UserTest extends DomainTestCase
{
    private $user;

    public function setUp()
    {
        $this->user = new User();
    }

    public function testAddRole()
    {
        $role1 = Role::create(Role::ADMIN);
        $role2 = Role::create(Role::PLAYER)->getRole();
        $user = $this->user;
        $user->addRole($role1);
        $user->addRole($role2);
        $roles = $user->getRoles();
        $this->assertEquals(['ROLE_ADMIN', 'ROLE_PLAYER'], $roles);
    }

    public function testIsValidUserRole()
    {
        $user =  $this->user;
        $role1 = Role::create(Role::ADMIN);
        $user->addRole($role1);
        $this->assertTrue($this->invokeMethod($user, 'isValidRole', [$role1->getRole()]));
    }

    public function testIsValidStringRole()
    {
        $this->assertTrue($this->invokeMethod($this->user, 'isValidRole', ['ROLE_PLAYER']));
    }

    public function testIsInvalidDomainRole()
    {
        $this->assertFalse($this->invokeMethod($this->user, 'isValidRole', ['ROLE_NOT_DEFINED']));
    }

    public function testIsInvalidRole()
    {
        $this->assertFalse($this->invokeMethod($this->user, 'isValidRole', ['DOESNT_FOLLOW_CONVENTION']));
        $this->assertFalse($this->invokeMethod($this->user, 'isValidRole', [null]));
        $this->assertFalse($this->invokeMethod($this->user, 'isValidRole', [array('')]));
        $this->assertFalse($this->invokeMethod($this->user, 'isValidRole', [0]));
    }

    public function testCreateGame()
    {
        $this->assertTrue(false);
    }

    public function testValidateResult()
    {
        $this->assertTrue(false);
    }
}

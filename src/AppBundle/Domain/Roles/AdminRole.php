<?php

namespace AppBundle\Domain\Roles;

use AppBundle\Domain\Role;

class AdminRole extends Role
{
    public function getRole()
    {
        return self::ADMIN;
    }

    public function getName()
    {
        return 'Administrator';
    }

    public function getCode()
    {
        return 'ROLE_SUPER_'.
            'ADMIN';
    }
}
<?php

namespace AppBundle\Domain\Roles;

use AppBundle\Domain\Role;

class AdminRole extends Role
{
    public function getCode()
    {
        return self::ADMIN;
    }

    public function getName()
    {
        return 'Administrator';
    }
}
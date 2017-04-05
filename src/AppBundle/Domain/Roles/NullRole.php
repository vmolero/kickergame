<?php

namespace AppBundle\Domain\Roles;

use AppBundle\Domain\Role;

class NullRole extends Role
{
    public function getRole()
    {
        return self::NULL;
    }

    public function getName()
    {
        return '';
    }

    public function getCode()
    {
        return '';
    }
}
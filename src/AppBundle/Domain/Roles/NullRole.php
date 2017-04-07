<?php

namespace AppBundle\Domain\Roles;

use AppBundle\Domain\Role;

class NullRole extends Role
{
    public function getCode()
    {
        return null;
    }

    public function getName()
    {
        return '';
    }
}
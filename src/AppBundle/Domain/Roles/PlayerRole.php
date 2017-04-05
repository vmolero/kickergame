<?php

namespace AppBundle\Domain\Roles;

use AppBundle\Domain\Role;

class PlayerRole extends Role
{
    public function getRole()
    {
        return self::PLAYER;
    }

    public function getName()
    {
        return 'Player';
    }

    public function getCode()
    {
        return 'ROLE_USER';
    }
}
<?php

namespace AppBundle\Domain\Roles;

use AppBundle\Domain\Role;

class PlayerRole extends Role
{

    public function getName()
    {
        return 'Player';
    }

    public function getCode()
    {
        return self::PLAYER;
    }
}
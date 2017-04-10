<?php

namespace AppBundle\Domain;

use AppBundle\Domain\Roles\AdminRole;
use AppBundle\Domain\Roles\NullRole;
use AppBundle\Domain\Roles\PlayerRole;
use AppBundle\Domain\Roles\RoleHolder;
use AppBundle\Entity\Role as RoleModel;

abstract class Role extends RoleModel implements RoleHolder
{
    const ADMIN = 'ROLE_ADMIN';
    const PLAYER = 'ROLE_PLAYER';

    public static function create($string)
    {
        $c = new NullRole();
        switch ($string)
        {
            case self::ADMIN:
            case 'admin':
                $c = new AdminRole();
                break;
            case self::PLAYER:
            case 'player':
                $c = new PlayerRole();
                break;
        }

        return $c;
    }

    public static function getValidRoles() {
        return [self::ADMIN, self::PLAYER];
    }
}
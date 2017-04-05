<?php

namespace AppBundle\Domain;

use AppBundle\Domain\Roles\AdminRole;
use AppBundle\Domain\Roles\NullRole;
use AppBundle\Domain\Roles\PlayerRole;
use AppBundle\Domain\Roles\RoleHolder;
use AppBundle\Entity\Role as RoleModel;

abstract class Role extends RoleModel implements RoleHolder
{
    const NULL = 0;
    const ADMIN = 1;
    const PLAYER = 2;

    public static function create($string)
    {
        $c = new NullRole();
        switch ($string)
        {
            case self::ADMIN:
            case 'ROLE_ADMIN':
            case 'admin':
                $c = new AdminRole();
                break;
            case self::PLAYER:
            case 'player':
            case 'ROLE_USER':
            default:
                $c = new PlayerRole();
                break;
        }

        return $c;
    }

}